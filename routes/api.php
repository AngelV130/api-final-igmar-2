<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthVerifySessionController;
use App\Http\Controllers\Auth\AuthVerifyUserController;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use App\Http\Controllers\Store\CategoryController;
use App\Http\Controllers\Store\ProductsController;


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


Route::controller(AuthVerifySessionController::class)->group(function () {
    Route::post('register', 'register');

    Route::post('login', 'login')->name('login');

    Route::post('logout', 'lgout')->name('logout')->middleware('auth:sanctum');

});

Route::controller(AuthVerifyUserController::class)->group(function () {
    Route::get('verify/email/{id}','activeAccount')->name('active.account')->whereNumber('id')->middleware('signed');
    
    Route::post('verify/code/{id}', 'verifyCode')->name('verify.code')->whereNumber('id')->middleware('signed');

    Route::post('code/mobile', 'verifyCodeMovil')->name('code.mobile')->middleware('rol:1');
});

Route::group(['middleware' => ['auth:sanctum']],function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('perfil', 'perfil')->name('perfil.info');
        Route::get('roles', 'getRoles')->name('roles.info')->middleware('rol:1');
        Route::get('users', 'users')->name('users.info')->middleware('rol:1');
        Route::get('user/{id}', 'getuser')->name('user.info')->whereNumber('id')->middleware('rol:1');
        Route::put('user/{id}', 'edit')->name('perfil.edit')->whereNumber('id')->middleware('rol:1');
        
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::get('/categories/{id}', 'index')->name('categories.info')->middleware('rol:1,2');
        Route::post('/categories', 'create')->name('categories.create')->middleware('rol:1,2');
        Route::put('/categories/{id}', 'edit')->name('categories.edit')->whereNumber('id')->middleware('rol:1,2');
        Route::delete('/categories/{id}', 'destroy')->name('categories.delete')->whereNumber('id')->middleware('rol:1,2');
    });
    Route::controller(ProductsController::class)->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::get('/products/{id}', 'index')->name('products.info')->middleware('rol:1,2');
        Route::post('/products', 'create')->name('products.create')->middleware('rol:1,2');
        Route::put('/products/{id}', 'edit')->name('products.edit')->whereNumber('id')->middleware('rol:1,2');
        Route::delete('/products/{id}', 'destroy')->name('products.delete')->whereNumber('id')->middleware('rol:1,2');
    });
});



/**
 * Otras Rutas
 */


