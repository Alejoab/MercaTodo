<?php

use App\Http\Controllers\Administrator\AdminController;
use App\Http\Controllers\Administrator\AdminCustomerController;
use App\Http\Controllers\Administrator\AdminProductController;
use App\Http\Controllers\Administrator\AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Models\City;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('cities/{state_id}', function ($state_id) {
    return City::query()->where('department_id', $state_id)->get();
})->name('cities');

Route::prefix('admin')->middleware(['auth', 'verified', 'role:Administrator'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [AdminUserController::class, 'userShow'])->name('admin.user.show');
    Route::put('/users/{id}', [AdminUserController::class, 'userUpdate'])->name('admin.user.update');
    Route::put('/users/{id}/password', [AdminUserController::class, 'userUpdatePassword'])->name('admin.user.update.password');
    Route::delete('/users/{id}', [AdminUserController::class, 'userDestroy'])->name('admin.user.destroy');
    Route::put('/users/{id}/restore', [AdminUserController::class, 'userRestore'])->name('admin.user.restore');
    Route::delete('/users/{id}/force-delete', [AdminUserController::class, 'userForceDelete'])->name('admin.user.force-delete');
    Route::get('/list-users', [AdminUserController::class, 'listUsers'])->name('admin.list-users');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'customerShow'])->name('admin.customer.show');
    Route::put('/customers/{id}', [AdminCustomerController::class, 'customerUpdate'])->name('admin.customer.update');
    Route::get('/list-customers', [AdminCustomerController::class, 'listCustomers'])->name('admin.list-customers');

    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products/create', [AdminProductController::class, 'store'])->name('admin.products.create');
    Route::get('/products/categories', [AdminProductController::class, 'searchCategories'])->name('admin.products.categories.search');
    Route::get('/products/brands', [AdminProductController::class, 'searchBrands'])->name('admin.products.brands.search');
});

require __DIR__ . '/auth.php';
