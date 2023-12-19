<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\GeneralOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use GeneralOutput;
    function show(Request $request)
    {
        $filters = [];
        $request->filled('active') ? $filters[] = ['active', '=', $request->active] : 0;
        $request->filled('name') ? $filters[] = ['name', 'like', "%{$request->name}%"] : 0;
        $request->filled('id') ? $filters[] = ['id', '=', $request->id] : 0;
        $request->filled('item_id') ? $filters[] = ['item_id', '=', $request->id] : 0;

        $user = User::where($filters)
            ->when($request->role_id != [], function ($query) use ($request) {
                return $query->whereIn('role_id', $request->role_id);
            })->get();

        return $this->returnData(
            'data',
            UserResource::collection($user),
        );
    }

    function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());
        $request->filled('item_id') ? $user->chef()->create($request->only('item_id')) : 0;
        return $this->returnData(
            'data',
            $user,
            'success create'
        );
    }
    function update(UserUpdateRequest $request, $id)
    {
        $validate = validator(['id' => $id], ['id' => 'exists:users,id']);
        if ($validate->fails())
            return $this->returnError($validate->errors()->getMessages());

        $user = User::findOrFail($id);
        $user->update($request->all());
        $request->filled('item_id') ? $user->chef()->update($request->only('item_id')) : 0;
        if (!$user->active) {
            $user->tokens()->delete();
        }
        return $this->returnData(
            'data',
            $user,
            'success update'
        );
    }

    function destroy($id)
    {
        $validate = validator(['id' => $id], ['id' => 'exists:users,id']);
        if ($validate->fails())
            return $this->returnError($validate->errors()->getMessages());

        $user = User::findOrFail($id);
        $user->update(['active' => false]);
        $user->tokens()->delete();

        return $this->returnData(
            'data',
            $user,
            'success user status changed'
        );
    }

    function login(Request $request)
    {
        $ability = ['0'];
        $request->validate([
            'email' => 'email|exists:users,email|required',
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
                $ability = ['editItem', 'editOrder'];
                break;
            case 4:
                $ability = ['editItem'];
                break;
            default:
                $ability = ['showItem', 'showOrder'];
        }
        if ($user->active && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('my', $ability)->plainTextToken;
            return $this->returnData('token', $token, 'success login');
        } else {
            return $this->returnError('error');
        }
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->returnSuccessMessage("success logout");
    }
}
