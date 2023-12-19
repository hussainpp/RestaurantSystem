<?php

namespace App\Http\Controllers;

use App\Providers\CreatedOrder;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateItemRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\chef;
use App\Models\order;
use App\Models\orderItem;
use App\Models\promoCode;
use App\Traits\GeneralOutput;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   use GeneralOutput;
   function store(OrderStoreRequest $request)
   {
      $request->filled('promo_code')
         ? $code = promoCode::where('code', $request->promo_code)->first() : 0;

      Auth::check()
         ? $order = order::create($request->except('user_id', 'promo_code_id')
            + ['user_id' => Auth::id(), 'promo_code_id' => $code->id ?? null])
         : $order = order::create($request->except('user_id', 'promo_code_id', 'type_order_id')
            + ['user_id' => null, 'type_order_id' => 3, 'promo_code_id' => $code->id ?? null]);

      $orderItems = $order->orderItem()->createMany($request->item);

      /**
       * send notification 
       */
      foreach ($orderItems as $orderItem) {
         $chef = chef::where('item_id', $orderItem->item_id)->first();
         $a = $orderItem->toArray() + ['user_id' => $chef->user_id ?? null];
         CreatedOrder::dispatch($a);
      }
      return $this->returnData(
         'data',
         $order->with('orderItem')->where('id', $order->id)->get(),
         'success create'
      );
   }

   function update(OrderUpdateRequest $request, $id)
   {
      $request->filled('promo_code') ? $code = promoCode::where('code', $request->promo_code)->first() : 0;

      $validate = validator(['id' => $id], ['id' => 'exists:orders,id']);
      if ($validate->fails())
         return $this->returnError($validate->errors()->getMessages());

      $order = order::findOrFail($id);
      if ($order->type_order_id == 1 && $request->state_order_id == 2) {
         return $this->returnError('The state_order_id is invalid.');
      }
      Auth::check()
         ? $order->update($request->except('user_id', 'promo_code_id')
            + ['user_id' => Auth::id(), 'promo_code_id' => $code->id ?? $order->promo_code_id])
         : $order->update($request->except('user_id', 'promo_code_id', 'type_order_id')
            + ['promo_code_id' => $code->id ?? $order->promo_code_id]);

      // return var_dump($order->whereExists("state_order_id",3)->get());

      return $this->returnData('data', $order, 'success update');
   }

   function updateItemOfOrder(OrderUpdateItemRequest $request)
   {
      $orders = [];
      foreach ($request->item as $it)
         $orders[] = orderItem::updateOrCreate(
            ['item_id' => $it['item_id'], 'order_id' => $it['order_id']],
            ['quantity' => $it['quantity']]
         );
      // $order=orderItem::upsert(
      //    $request->item,['quantity']
      // );
      return $this->returnData('data', $orders, 'success update');
   }

   function deleteItemOfOrder($id)
   {
      $validate = validator(['id' => $id], ['id' => 'exists:order_items,id']);
      if ($validate->fails())
         return $this->returnError($validate->errors()->getMessages());

      orderItem::findOrFail($id)->delete();
      return $this->returnSuccessMessage("success delete");
   }

   function destroy($id)
   {
      $validate = validator(['id' => $id], ['id' => 'exists:orders,id']);
      if ($validate->fails())
         return $this->returnError($validate->errors()->getMessages());

      order::findOrFail($id)->delete();
      orderItem::where('order_id', $id)->delete();
      return $this->returnSuccessMessage("success delete");
   }

   function show(Request $request)
   {
      $filters = [];

      $request->filled('id') ? $filters[] = ['id', '=', $request->id] : 0;
      $request->filled('name') ? $filters[] = ['name', 'like', "%{$request->name}%"] : 0;
      $request->filled('from_created_at') ? $filters[] = ['created_at', '>=', $request->from_created_at] : 0;
      $request->filled('to_created_at') ? $filters[] = [
         'created_at', '<=',
         Carbon::hasFormatWithModifiers($request->to_created_at, 'Y#m#d *')
            ? $request->to_created_at : $request->to_created_at . ' 23:59:59'
      ] : 0;

      $request->filled('user_id') ? $filters[] = ['user_id', '=', $request->user_id] : 0;

      $order = order::where($filters)
         ->when($request->type_order_id != [], function ($query) use ($request) {
            return $query->whereIn('type_order_id', $request->type_order_id);
         })
         ->when($request->state_order_id != [], function ($query) use ($request) {
            return $query->whereIn('state_order_id', $request->state_order_id);
         })
         ->get();
      $order = $order->where('total_price', '>=', $request->from_price ?? 0)
         ->where('total_price', '<=', $request->to_price ?? "1`or 1=1");
      return $this->returnData('data', OrderResource::collection($order));
   }

   function report(Request $request)
   {
      $filters = [];
      $filtersForTopItem = [];
      $request->filled('state_order_id') ? $filters[] = ['state_order_id', $request->state_order_id] : 0;
      $request->filled('type_order_id') ? $filters[] = ['type_order_id', '=', $request->type_order_id] : 0;

      $request->filled('state_order_id') ? $filtersForTopItem[] = [function (Builder $query) {
         $query->select('state_order_id')
            ->from('orders')
            ->whereColumn('orders.id', 'order_items.order_id');
      }, $request->state_order_id] : 0;

      $request->filled('type_order_id') ? $filtersForTopItem[] = [function (Builder $query) {
         $query->select('type_order_id')
            ->from('orders')
            ->whereColumn('orders.id', 'order_items.order_id');
      }, $request->type_order_id] : 0;

      $stateOrder = order::selectRaw('GROUP_CONCAT(DISTINCT state_orders.name) as state_order_id, count(*) as total')
         ->where($filters)
         ->join('state_orders', 'state_order_id', '=', 'state_orders.id')
         ->groupBy('state_order_id')
         ->orderBy('total', 'desc')
         ->get();

      $typeOrder = order::selectRaw('GROUP_CONCAT(DISTINCT type_orders.name) as type_order_id, count(*) as total')
         ->where($filters)
         ->join('type_orders', 'type_order_id', '=', 'type_orders.id')
         ->groupBy('type_order_id')
         ->orderBy('total', 'desc')
         ->get();

      $topItemTen = OrderItem::selectRaw('GROUP_CONCAT(DISTINCT items.name) as item_id, count(*) as total')
         ->where($filtersForTopItem)
         ->join('items', 'item_id', '=', 'items.id')
         ->groupBy('item_id')
         ->orderBy('total', 'desc')->limit(10)
         ->get();

      $allOrder = $typeOrder->sum('total');
      return [
         'all_order' => $allOrder,
         'state_order' => $stateOrder,
         'type_order' => $typeOrder,
         'top_ten_items' => $topItemTen
      ];
   }
}
