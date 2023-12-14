<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuStoreRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Resources\ItemAllResource;
use App\Http\Resources\MenuResource;
use App\Models\item;
use App\Models\menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    function show(Request $request){
        $filters=[];
        $request->filled('from_price')?$filters[]=['price', '>=', $request->from_price] : 0;
        $request->filled('to_price')?$filters[]=['price', '<=', $request->from_price] : 0;
       // $filters[]=['items.active', '=', 1];
        $menu=menu::where($filters)->with('item')
        ->when($request->menu != [], function($q) use( $request) {
            return $q->where('name','like', $request->menu);
        })
        ->get();
       
          return MenuResource::collection($menu);
    }
    function showAll(Request $request){
        $filters=[];
        $request->filled('from_price')?$filters[]=['price', '>=', $request->from_price] : 0;
        $request->filled('to_price')?$filters[]=['price', '<=', $request->from_price] : 0;

        $menu=menu::where($filters)
        ->when($request->menu != [], function($q) use( $request) {
            return $q->where('name','like', $request->menu);
        })
        ->get();
        //    Dump( $ms->where('item->id','=',$request->item));
        // return [$ms[0]->item[0]];
        return ItemAllResource::collection($menu);
    }

    function store(MenuStoreRequest $request){
       $me=menu::create($request->all());
       return $me;
    }

    function update(MenuUpdateRequest $request,$id){
        $menu=menu::findOrFail($id);
        $menu->update($request->all());
       return $menu;
    }
    function destroy($id){
        $menu=menu::findOrFail($id)->delete();
       return $menu;
    }
}
