<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('product', ProductController::class);

Route::prefix('v1/admin')->name('admin.')->group(
    function (): void {
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::post('/create', [UserController::class, 'store'])->name(
            'store'
        );
        Route::get('/logout', [UserController::class, 'logout'])->name(
            'logout'
        );
        Route::middleware(['jwt:admin'])->group(
            function (): void {
                Route::get('/user-listing', [UserController::class, 'index'])
                    ->name('index');
                Route::put(
                    '/user-edit/{uuid}',
                    [UserController::class, 'update']
                )->name('update');
                Route::delete(
                    '/user-delete/{uuid}',
                    [UserController::class, 'destroy']
                )->name('destroy');
            }
        );
    }
);
