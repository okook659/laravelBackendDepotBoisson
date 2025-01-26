<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/auth', [FirebaseAuthController::class, 'auth'])->name('auth');
Route::post('/verify-token', [FirebaseAuthController::class, 'verifyToken'])->name('verifyToken');
Route::get('/test', function () {
    return response()->json(['message' => 'It works!']);
});
