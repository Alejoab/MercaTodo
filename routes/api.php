<?php

use App\Domain\Users\Enums\PermissionEnum;
use App\Domain\Users\Enums\RoleEnum;
use App\Http\Controllers\Api\Auth\LoginApiController;
use App\Http\Controllers\Api\Products\AdminProductApiController;
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
$admins = implode('|', [RoleEnum::ADMIN->value, RoleEnum::SUPER_ADMIN->value]);


Route::post('login', [LoginApiController::class, 'login'])->name('api.login');

Route::prefix('admin/products')->middleware(['auth:sanctum', 'verified', "role:$admins"])->group(function () {
    Route::middleware(['permission:'.PermissionEnum::CREATE->value])->post('/store', [AdminProductApiController::class, 'store'])->name('api.admin.products.store');
    Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->put('/{product}', [AdminProductApiController::class, 'update'])->name('api.admin.products.update');
});
