<?php

use App\Http\Controllers\Api\CallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/seller/register', [App\Http\Controllers\Api\AuthController::class, 'registerSeller']);
Route::post('/buyer/register', [App\Http\Controllers\Api\AuthController::class, 'registerBuyer']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

//category
Route::post('/seller/category', [App\Http\Controllers\Api\CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('/seller/categories', [App\Http\Controllers\Api\CategoryController::class, 'index'])->middleware('auth:sanctum');

//product
Route::get('/seller/products', [App\Http\Controllers\Api\ProductController::class, 'index'])->middleware('auth:sanctum');
Route::post('/seller/products', [App\Http\Controllers\Api\ProductController::class, 'store'])->middleware('auth:sanctum');
Route::post('/seller/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/seller/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'destory'])->middleware('auth:sanctum');
Route::get('/seller/products/count', [App\Http\Controllers\Api\ProductController::class, 'countBySeller'])->middleware('auth:sanctum');

//address
Route::get('/buyer/addresses', [App\Http\Controllers\Api\AddressController::class, 'index'])->middleware('auth:sanctum');
Route::post('/buyer/addresses', [App\Http\Controllers\Api\AddressController::class, 'store'])->middleware('auth:sanctum');
Route::post('/buyer/addresses/{id}', [App\Http\Controllers\Api\AddressController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/buyer/addresses/{id}', [App\Http\Controllers\Api\AddressController::class, 'destory'])->middleware('auth:sanctum');

//orders
Route::post('/buyer/orders', [App\Http\Controllers\Api\OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/buyer/stores', [App\Http\Controllers\Api\StoreController::class, 'index'])->middleware('auth:sanctum');
Route::get('/buyer/stores/{id}/products', [App\Http\Controllers\Api\StoreController::class, 'productByStore'])->middleware('auth:sanctum');
Route::put('/seller/orders/{id}/update-resi', [App\Http\Controllers\Api\OrderController::class, 'updateShippingNumber'])->middleware('auth:sanctum');
Route::get('/buyer/histories', [App\Http\Controllers\Api\OrderController::class, 'historyOrderBuyer'])->middleware('auth:sanctum');
Route::get('/seller/orders', [App\Http\Controllers\Api\OrderController::class, 'historyOrderSeller'])->middleware('auth:sanctum');
Route::get('/buyer/stores/livestreaming', [App\Http\Controllers\Api\StoreController::class, 'livestreaming'])->middleware('auth:sanctum');
// Route::get('/seller/orders/count', [App\Http\Controllers\Api\OrderController::class, 'countOrderSeller'])->middleware('auth:sanctum');

//callback
Route::post('/midtrans/callback', [CallbackController::class, 'callback']);


//check order status
Route::get('/buyer/orders/{id}/status', [App\Http\Controllers\Api\OrderController::class, 'checkOrderStatus'])->middleware('auth:sanctum');

//get order by id
Route::get('/orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'getOrderById'])->middleware('auth:sanctum');

//get agora token
Route::post('/agora/token', [App\Http\Controllers\Api\AgoraController::class, 'getToken'])->middleware('auth:sanctum');

//set is livestreaming
Route::post('/seller/livestreaming', [App\Http\Controllers\Api\StoreController::class, 'setLivestreaming'])->middleware('auth:sanctum');

//update fcm token
Route::put('/update-fcm-token', [App\Http\Controllers\Api\AuthController::class, 'updateFcmToken'])->middleware('auth:sanctum');


// Get profile for seller
Route::get('/seller/profile', [App\Http\Controllers\Api\AuthController::class, 'getSellerProfile'])->middleware('auth:sanctum');

// Update profile for seller
Route::post('/seller/profile/update', [App\Http\Controllers\Api\AuthController::class, 'updateSellerProfile'])->middleware('auth:sanctum');

// Get profile for buyer
Route::get('/buyer/profile', [App\Http\Controllers\Api\AuthController::class, 'getBuyerProfile'])->middleware('auth:sanctum');

// Update profile for buyer
Route::post('/buyer/profile/update', [App\Http\Controllers\Api\AuthController::class, 'updateBuyerProfile'])->middleware('auth:sanctum');
