<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuStoreRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Resources\ItemAllResource;
use App\Http\Resources\MenuResource;
use App\Models\item;
use App\Models\menu;
use App\Traits\GeneralOutput;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use GeneralOutput;
    function show(Request $request){
        $filters=[];
        $request->filled('from_price')?$filters[]=['price', '>=', $request->from_price] : 0;
        $request->filled('to_price')?$filters[]=['price', '<=', $request->to_price] : 0;
        
        $menu=menu::where($filters)->
        // with('item')
        // ->when($request->menu != [], function($q) use( $request) {
        //     return $q->where('name','like', $request->menu);
        // })
        //->join('items','menus.id','=','items.menu_id')
        get();
       //return $menu;
       return $this->returnData('foods',MenuResource::collection($menu));
    }
    function showAll(Request $request){
        $filters=[];
        $request->filled('from_price')?$filters[]=['price', '>=', $request->from_price] : 0;
        $request->filled('to_price')?$filters[]=['price', '<=', $request->to_price] : 0;

        $menu=menu::where($filters)
        ->when($request->menu != [], function($q) use( $request) {
            return $q->where('name','like', $request->menu);
        })
        ->get();

        return $this->returnData('foods',MenuResource::collection($menu));
    }

    function store(MenuStoreRequest $request){
        $menu=menu::create($request->all());

       return $this->returnData('data',$menu,'success create');
    }

    function update(MenuUpdateRequest $request,$id){
        $validate= validator(['id'=>$id],['id'=>'exists:menus,id|integer']);
        if( $validate->fails())
        return $this->returnError($validate->errors()->getMessages());

        $menu=menu::findOrFail($id);
        $menu->update($request->all());
       return $this->returnData('data',$menu,'success update');
    }
    function destroy($id){
       $validate= validator(['id'=>$id],['id'=>'exists:menus,id|integer']);
        if( $validate->fails())
        return $this->returnError($validate->errors()->getMessages());

        return $this->returnCheck(menu::find($id)->delete(),"success delete","fails delete");
       //return $val->errors()->all(':key :message');
    }
}
