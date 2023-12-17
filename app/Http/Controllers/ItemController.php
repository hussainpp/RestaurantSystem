<?php

namespace App\Http\Controllers;

use App\Traits\GeneralOutput;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\item;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    use GeneralOutput;
    function store(ItemStoreRequest $request){
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName = time().'.'. $image->extension();
            $image->move(public_path('upload'), $imageName);
        }
       $item=item::create($request->except('image')+['image'=> $imageName]);
       return $this->returnData('data',$item,'success create');
    }

    function update(ItemUpdateRequest $request,$id){
        $validate= validator(['id'=>$id],['id'=>'exists:items,id']);
        if($validate->fails())
        return $this->returnError($validate->errors()->getMessages());

        $item = item::findOrFail($id);
        $imagePath = Str::afterLast($item->image,'public/');
        file::delete(public_path($imagePath));

        if($request->hasFile('image')){
            $image=$request->file('image');
            $imagePath = time().'.'. $image->extension();
            $image->move(public_path('upload'), $imagePath);
        }
        $item->update($request->except('image')+['image'=> $imagePath]);
        
        return $this->returnData('data',$item,'success update');
    }
    function destroy($id){
        $validate= validator(['id'=>$id],['id'=>'exists:items,id']);
        if($validate->fails())
        return $this->returnError($validate->errors()->getMessages());

        $item=item::findOrFail($id);
        $path=Str::afterLast($item->image,"public/");
        file::delete(public_path($path));
        
        return $this->returnCheck($item->delete(),"success delete","fails delete");
    }
}
