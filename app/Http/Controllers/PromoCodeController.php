<?php

namespace App\Http\Controllers;

use App\Models\promoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    function store(Request $request){
        $request->validate([
            'code' => 'string|required',
            'discount' => 'numeric|required'
        ]);
        $promoCode=promoCode::create($request->all());
        return $promoCode;
     }
     function update(Request $request,$id){
      validator(['id'=>$id],['id'=>'exists:promo_codes,id'])->validated();
        $request->validate([
            'code' => 'string',
            'discount' => 'numeric'
        ]);
        $promoCode=promoCode::findOrFail($id);
        $promoCode->update($request->all());
        return $promoCode;
     }
     function destroy($id){
      validator(['id'=>$id],['id'=>'exists:promo_codes,id'])->validated();
        $promoCode=promoCode::findOrFail($id)->delete();
        return $promoCode;
     }

     function show(Request $request){
        $filters=[];
        $request->filled('id')?$filters[]=['id', '=', $request->id] : 0;
        $request->filled('code')?$filters[]=['code', 'like', "%{$request->code}%"] : 0;
        $request->filled('discount')?$filters[]=['discount',$request->discount] : 0;

        $promoCode=promoCode::where($filters)->get();
        return $promoCode;
     }

}
