<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('home');
});

Route::post('/logins/register', [UserController::class, 'register']);
Route::post('/logins/login-token', [UserController::class, 'login']);
Route::post('/logins/logout', [UserController::class, 'logout']);


Route::get('/items', function () {
    return view('items'); 
});

Route::get('/items', [ItemController::class, 'showItems'])->name('items.index');
Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
Route::patch('/items/{id}/changeitemtype', [ItemController::class, 'changeItemType']);
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

Route::get('/test-header', function () {
    return view('test');
});
