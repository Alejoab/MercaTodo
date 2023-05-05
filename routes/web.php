<?php

use App\Http\Controllers\Administrator\AdminController;
use App\Http\Controllers\Administrator\AdminCustomerController;
use App\Http\Controllers\Administrator\AdminProductController;
use App\Http\Controllers\Administrator\AdminUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('cities/{id}', [CityController::class, 'citiesByDepartment'])->name('cities');
Route::get('departments', [CityController::class, 'departments'])->name('departments');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('', [UserController::class, 'index'])->name('home');
    Route::get('categories', [CategoryController::class, 'list'])->name('categories');
    Route::get('brands/{id?}', [BrandController::class, 'brandsByCategory'])->name('brands');
    Route::get('list-products', [ProductController::class, 'listProducts'])->name('list-products');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
});

Route::prefix('admin')->middleware(['auth', 'verified', 'role:Administrator'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{user}', [AdminUserController::class, 'userShow'])->withTrashed()->name('admin.user.show');
    Route::put('/users/{user}', [AdminUserController::class, 'userUpdate'])->withTrashed()->name('admin.user.update');
    Route::put('/users/{user}/password', [AdminUserController::class, 'userUpdatePassword'])->withTrashed()->name('admin.user.update.password');
    Route::delete('/users/{user}', [AdminUserController::class, 'userDestroy'])->name('admin.user.destroy');
    Route::put('/users/{user}/restore', [AdminUserController::class, 'userRestore'])->withTrashed()->name('admin.user.restore');
    Route::delete('/users/{user}/force-delete', [AdminUserController::class, 'userForceDelete'])->withTrashed()->name('admin.user.force-delete');
    Route::get('/list-users', [AdminUserController::class, 'listUsers'])->name('admin.list-users');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'customerShow'])->withTrashed()->name('admin.customer.show');
    Route::put('/customers/{user}', [AdminCustomerController::class, 'customerUpdate'])->withTrashed()->name('admin.customer.update');
    Route::get('/list-customers', [AdminCustomerController::class, 'listCustomers'])->name('admin.list-customers');

    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products/create', [AdminProductController::class, 'store'])->name('admin.products.create');
    Route::get('/products/{id}', [AdminProductController::class, 'productShow'])->name('admin.products.show');
    Route::post('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::put('/products/{id}/restore', [AdminProductController::class, 'restore'])->name('admin.products.restore');
    Route::delete('/products/{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('admin.products.force-delete');
    Route::get('/categories', [AdminProductController::class, 'searchCategories'])->name('admin.categories.search');
    Route::get('/brands', [AdminProductController::class, 'searchBrands'])->name('admin.brands.search');
    Route::get('/list-products', [AdminProductController::class, 'listProducts'])->name('admin.list-products');
});

require __DIR__.'/auth.php';
