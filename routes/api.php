<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login',[UserController::class,'login']);
Route::get('logout',[UserController::class,'logout']);
Route::get('menu',[menuController::class,'show']);
Route::post('order',[orderController::class,'store']);

Route::get('report',[orderController::class,'report'])->middleware(['auth:sanctum', 'abilities:*']);



Route::group(["prefix"=>'menu',"controller"=>MenuController::class,
'middleware' => ['auth:sanctum', 'abilities:editItem']],function(){
    Route::get('show','show')->withoutMiddleware(['abilities:editItem']);
    Route::post('store','store');
    Route::post('update/{menu_id}','update');
    Route::get('destroy/{menu_id}','destroy');
});

Route::group(["prefix"=>'item',"controller"=>ItemController::class,
'middleware' => ['auth:sanctum', 'abilities:editItem']],function(){
    //Route::get('show','show')->withoutMiddleware('abilities:editItem');
    Route::post('store','store');
    Route::post('update/{item_id}','update');
    Route::get('destroy/{item_id}','destroy');
});

Route::group(["prefix"=>'user',"controller"=>UserController::class,
'middleware' => ['auth:sanctum', 'abilities:*']],function(){
    Route::get('show','show');
    Route::post('store','store');
    Route::post('update/{user_id}','update');
    Route::get('destroy/{user_id}','destroy');
});

Route::group(["prefix"=>'order',"controller"=>OrderController::class,
'middleware' => ['auth:sanctum', 'abilities:editOrder']],function(){
    Route::get('show','show')->withoutMiddleware('abilities:editOrder');
    Route::post('store','store');
    Route::post('update/{order_id}','update');
    Route::get('destroy/{order_id}','destroy');
    Route::post('updateItem','updateItemOfOrder');
    Route::post('deleteItem/{item_id}','deleteItemOfOrder');
});

Route::group(["prefix"=>'promo',"controller"=>PromoCodeController::class,
'middleware' => ['auth:sanctum', 'abilities:*']],function(){
    Route::get('show','show')->withoutMiddleware('abilities:*');
    Route::post('store','store');
    Route::post('update/{promo_id}','update');
    Route::get('destroy/{promo_id}','destroy');
});


