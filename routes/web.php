<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Application;
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
    Route::get('/list-users', [AdminUserController::class, 'listUsers'])->name('admin.list-users');


    Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('admin.user.show');
    Route::patch('/users/{id}', [AdminController::class, 'userUpdate'])->name('admin.user.update');
    Route::patch('/users-address/{id}', [AdminController::class, 'userUpdateAddress'])->name('admin.user.update.address');
    Route::put('/users-password/{id}', [AdminController::class, 'userUpdatePassword'])->name('admin.user.update.password');
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('admin.user.destroy');
    Route::put('/users/{id}', [AdminController::class, 'userRestore'])->name('admin.user.restore');
    Route::delete('/users-force-delete/{id}', [AdminController::class, 'userForceDelete'])->name('admin.user.force-delete');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'customerShow'])->name('admin.customer.show');
    Route::put('/customers/{id}', [AdminCustomerController::class, 'customerUpdate'])->name('admin.customer.update');
    Route::get('/list-customers', [AdminCustomerController::class, 'listCustomers'])->name('admin.list-customers');
});

require __DIR__.'/auth.php';
