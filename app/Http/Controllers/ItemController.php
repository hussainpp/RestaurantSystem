<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    function store(ItemStoreRequest $request){
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName = time().'.'. $image->extension();
            $image->move(public_path('upload'), $imageName);
        }
       $me=item::create($request->except('image')+['image'=> $imageName]);
       return $me;
    }

    function update(ItemUpdateRequest $request,$id){
        $item = item::findOrFail($id);
         $imagePath = Str::afterLast($item->image,'public/');
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName = time().'.'. $image->extension();
            $image->move(public_path('upload'), $imageName);
            $item->update($request->except('image')+['image'=> $imageName]);
            file::delete(public_path($imagePath));
            return $item;

        }
        $item->update($request->except('image'));
        return $item;
    }
    function destroy($id){
        $item=item::findOrFail($id);
        $path=Str::afterLast($item->image,"public/");
        file::delete(public_path($path));
        $item->delete();
        if($item)
        return response()->json(['message'=>'success delete']);
    else
    return response()->json(['message'=>'fail delete']);

    }
}
