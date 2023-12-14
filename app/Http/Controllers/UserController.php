<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\chef;
use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function show(Request $request)
    {
        $filters = [];
        $request->filled('active') ? $filters[] = ['active', '=', $request->active] : 0;
        $request->filled('name') ? $filters[] = ['name', 'like', "%{$request->name}%"] : 0;
        $request->filled('id') ? $filters[] = ['id', '=', $request->id] : 0;
        $request->filled('item_id') ? $filters[] = ['item_id', '=', $request->id] : 0;
        // $request->filled('role_id')?$filters[]=['role_id', '=', $request->id] : 0;

        $ms = User::where($filters)
            ->when($request->role_id != [], function ($q) use ($request) {
                return $q->whereIn('role_id', $request->role_id);
            })->get();

        return UserResource::collection($ms);
    }

    function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());
        $request->filled('item_id') ? $user->chef()->create($request->only('item_id')) : 0;
        return $user;
    }
    function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        $request->filled('item_id') ? $user->chef()->update($request->only('item_id')) : 0;
        if (!$user->active) {
            $user->tokens()->delete();
        }
        return $user;
    }

    function destroy($id)
    {
        $item = User::findOrFail($id)->update(['active' => false]);
        return $item;
    }

    function login(Request $request)
    {
        $ability = ['0'];
        $request->validate([
            'email' => 'email|required',
            'password' => 'string|required'
        ]);

        $user = User::where('email', $request->email)->first();
        switch ($user->role_id) {
            case 1:
                $ability = ['*'];
                break;
            case 2:
                $ability = ['editItem'];
                break;
            case 3:
                $ability = ['editItem','editOrder'];
                break;
            case 4:
                $ability = ['editItem'];
                break;
            default:
               $ability=['showItem','showOrder'];
        }
        if ($user->active && Hash::check($request->password, $user->password)) {
            return $user->createToken('my', $ability)->plainTextToken;
        } else {
            return 'error';
        }
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
