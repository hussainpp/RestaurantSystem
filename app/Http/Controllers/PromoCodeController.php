<?php

namespace App\Http\Controllers;

use App\Models\promoCode;
use Illuminate\Http\Request;
use App\Traits\GeneralOutput;


class PromoCodeController extends Controller
{
    use GeneralOutput;

    function store(Request $request)
    {
        $request->validate([
            'code' => 'string|required',
            'discount' => 'numeric|required'
        ]);
        $promoCode = promoCode::create($request->all());
        return $this->returnData(
            'data',
            $promoCode,
            'success create'
        );
    }
    function update(Request $request, $id)
    {
        validator(['id' => $id], ['id' => 'exists:promo_codes,id'])->validated();
        $request->validate([
            'code' => 'string',
            'discount' => 'numeric'
        ]);
        $promoCode = promoCode::findOrFail($id);
        $promoCode->update($request->all());
        return $this->returnData(
            'data',
            $promoCode,
            'success update'
        );
    }
    function destroy($id)
    {
        $validate = validator(['id' => $id], ['id' => 'exists:promo_codes,id']);
        if ($validate->fails())
            return $this->returnError($validate->errors()->getMessages());
        promoCode::findOrFail($id)->delete();
        return $this->returnSuccessMessage("success delete");
    }

    function show(Request $request)
    {
        $filters = [];
        $request->filled('id') ? $filters[] = ['id', '=', $request->id] : 0;
        $request->filled('code') ? $filters[] = ['code', 'like', "%{$request->code}%"] : 0;
        $request->filled('discount') ? $filters[] = ['discount', $request->discount] : 0;

        $promoCode = promoCode::where($filters)->get();
        return $this->returnData(
            'data',
            $promoCode,
        );
    }
}
