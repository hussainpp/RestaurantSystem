<?php

namespace App\Http\Controllers;

use App\Providers\CreatedOrder;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\chef;
use App\Models\order;
use App\Models\orderItem;
use App\Models\promoCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   function store(OrderStoreRequest $request)
   {
      $request->filled('promo_code') ? $code = promoCode::where('code', $request->promo_code)->first()
         : 0;

      Auth::check()
         ? $order = order::create($request->except('user_id', 'promo_code_id')
            + ['user_id' => Auth::id(), 'promo_code_id' => $code->id ?? null])
         : $order = order::create($request->except('user_id', 'promo_code_id', 'type_order_id')
            + ['user_id' => null, 'type_order_id' => 3, 'promo_code_id' => $code->id ?? null]);

      $it = $order->orderItem()->createMany($request->item);

      /**
       * send notification 
       */
      foreach ($it as $i) {
         $ch = chef::where('item_id', $i->item_id)->first();
         $a = $i->toArray() + ['user_id' => $ch->user_id ?? null];
         CreatedOrder::dispatch($a);
      }
      //CreatedOrder::dispatch(orderItem::where('order_id',$order->id)->get());

      return  $order->with('orderItem')->where('id', $order->id)->get();
   }

   function update(OrderUpdateRequest $request, $id)
   {
      $request->filled('promo_code') ? $code = promoCode::where('code', $request->promo_code)->first() : 0;

      $order = order::findOrFail($id);

      Auth::check()
         ? $order->update($request->except('user_id', 'promo_code_id')
            + ['user_id' => Auth::id(), 'promo_code_id' => $code->id ?? $order->promo_code_id])
         : $order->update($request->except('user_id', 'promo_code_id', 'type_order_id')
            + ['promo_code_id' => $code->id ?? $order->promo_code_id]);

      return $order;
   }

   function updateItemOfOrder(Request $request, $id)
   {
      $validated = $request->validate([
         'item_id' => "exists:items,id",
         'quantity' => 'numeric'
      ]);
      $order = orderItem::findOrFail($id);
      $order->update($request->except('order_id'));
      //$or=$order->orderItem()->createMany($request->item);
      return $order;
   }

   function deleteItemOfOrder($id)
   {
      $order = orderItem::findOrFail($id)->delete();
      return $order;
   }

   function destroy($id)
   {
      $order = order::findOrFail($id)->delete();
      orderItem::where('order_id', $id)->delete();
      return $order;
   }
   function show(Request $request)
   {
      $filters = [];
      $request->filled('id') ? $filters[] = ['id', '=', $request->id] : 0;
      $request->filled('name') ? $filters[] = ['name', 'like', "%{$request->name}%"] : 0;
      $request->filled('from_price') ? $filters[] = ['total_price','>=',$request->from_price]:0;
      $request->filled('to_price') ? $filters[] = ['total_price','<=',$request->to_price]:0;
      $request->filled('from_created_at') ? $filters[] = ['created_at','>=',$request->from_created_at]:0;
      $request->filled('to_created_at') ? $filters[] = ['created_at','<=',$request->to_created_at]:0;
      $request->filled('user_id') ? $filters[] = ['user_id','=',$request->user_id]:0;

      $order = order::where($filters)->
      when($request->type_order_id,function($q) use($request){
         return $q->whereIn('type_order_id', $request->type_order_id);
      })
      ->get();

    $order->where($filters);
      return OrderResource::collection($order);
   }

   function report(Request $request)
   {
      $filters = [];
      $request->filled('stage_order_id') ? $filters[] = ['stage_order_id', '=', $request->stage_order_id] : 0;
      
      $typeOrder = order::selectRaw('GROUP_CONCAT(DISTINCT type_orders.name) as type_order_id, count(*) as total')
         ->where($filters)
         ->join('type_orders', 'type_order_id', '=', 'type_orders.id')
         ->groupBy('type_order_id')
         ->orderBy('total', 'desc')
         ->get();

      $topItemTen = OrderItem::selectRaw('GROUP_CONCAT(DISTINCT items.name) as item_id, count(*) as total')
         ->join('items', 'item_id', '=', 'items.id')
         ->groupBy('item_id')
         ->orderBy('total', 'desc')->limit(10)
         ->get();

      $typeOrde = order::whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->count();
      //echo Carbon::yesterday();
      $allOrder = $typeOrder->sum('total');
      return [
         'all_order' => $allOrder,
         'type_order' => $typeOrde,
         'top_ten_items' => $topItemTen
      ];
   }
}
