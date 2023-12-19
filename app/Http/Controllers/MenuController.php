<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuStoreRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Resources\ItemAllResource;
use App\Http\Resources\MenuResource;
use App\Models\item;
use App\Models\menu;
use App\Traits\GeneralOutput;
use Illuminate\Contracts\Database\Eloquent\Builder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    use GeneralOutput;
    function show(Request $request)
    {
        $filters = [];
        $filterInside = [];
        if (Auth::check())
            $request->filled('active') ? $filterInside[] = ['active', '=', $request->active] : 0;
        else
            $filterInside[] = ['active', '=', 1];

        $request->filled('name') ? $filters[] = ['name', 'like', "%$request->name%"] : 0;
        $request->filled('name_item') ? $filterInside[] = ['name', 'like', "%$request->name_item%"] : 0;
        $request->filled('from_price') ? $filterInside[] = ['price', '>=', $request->from_price] : 0;
        $request->filled('to_price') ? $filterInside[] = ['price', '<=', $request->to_price] : 0;
        $request->filled('from_preparation_time') ? $filterInside[] = ['preparation_time', '>=', $request->from_preparation_time] : 0;
        $request->filled('to_preparation_time') ? $filterInside[] = ['preparation_time', '<=', $request->to_preparation_time] : 0;

        $menu = menu::where($filters)->with(['item' => function (Builder $query) use ($filterInside) {
            $query->where($filterInside);
        }])->get();
        return $this->returnData('foods', MenuResource::collection($menu));
    }

    function store(MenuStoreRequest $request)
    {
        $menu = menu::create($request->all());

        return $this->returnData('data', $menu, 'success create');
    }

    function update(MenuUpdateRequest $request, $id)
    {
        $validate = validator(['id' => $id], ['id' => 'exists:menus,id|integer']);
        if ($validate->fails())
            return $this->returnError($validate->errors()->getMessages());

        $menu = menu::findOrFail($id);
        $menu->update($request->all());
        return $this->returnData('data', $menu, 'success update');
    }
    function destroy($id)
    {
        $validate = validator(['id' => $id], ['id' => 'exists:menus,id|integer']);
        if ($validate->fails())
            return $this->returnError($validate->errors()->getMessages());

        return $this->returnCheck(menu::find($id)->delete(), "success delete", "fails delete");
        //return $val->errors()->all(':key :message');
    }
}
