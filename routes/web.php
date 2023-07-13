<?php

use App\Domain\Users\Enums\PermissionEnum;
use App\Domain\Users\Enums\RoleEnum;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\Carts\CartController;
use App\Http\Controllers\Web\Customers\AdminCustomerController;
use App\Http\Controllers\Web\Customers\CityController;
use App\Http\Controllers\Web\Payments\PaymentController;
use App\Http\Controllers\Web\Products\AdminExportController;
use App\Http\Controllers\Web\Products\AdminImportController;
use App\Http\Controllers\Web\Products\AdminProductController;
use App\Http\Controllers\Web\Products\BrandController;
use App\Http\Controllers\Web\Products\CategoryController;
use App\Http\Controllers\Web\Products\ProductController;
use App\Http\Controllers\Web\Reports\AdminReportController;
use App\Http\Controllers\Web\Users\AdminUserController;
use App\Http\Controllers\Web\Users\ProfileController;
use App\Http\Controllers\Web\Users\UserController;
use Illuminate\Support\Facades\Route;


$admins = implode('|', [RoleEnum::ADMIN->value, RoleEnum::SUPER_ADMIN->value]);

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

    Route::get('order-history', [ProfileController::class, 'orderHistory'])->name('order.history');
});


Route::middleware([])->group(function () {
    Route::get('', [UserController::class, 'index'])->name('home');
    Route::get('categories', [CategoryController::class, 'list'])->name('categories');
    Route::get('brands/{id?}', [BrandController::class, 'brandsByCategory'])->name('brands');
    Route::get('list-products', [ProductController::class, 'listProducts'])->name('list-products');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('product-information/{product}', [ProductController::class, 'productInformation'])->withTrashed()->name('product.information');
    Route::get('cities/{id}', [CityController::class, 'citiesByDepartment'])->name('cities');
    Route::get('departments', [CityController::class, 'departments'])->name('departments');
    Route::get('cart-items-count', [CartController::class, 'getNumberOfItems'])->name('cart.count');
});

Route::prefix('cart')->middleware(['auth', 'verified'])->group(function () {
    Route::get('', [CartController::class, 'index'])->name('cart');
    Route::post('/add-product', [CartController::class, 'addProductToCart'])->name('cart.add');
    Route::delete('/delete-product', [CartController::class, 'deleteProductToCart'])->name('cart.delete');
});

Route::prefix('payment')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [PaymentController::class, 'pay'])->name('cart.buy');
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('/retry', [PaymentController::class, 'retry'])->name('payment.retry');
});

Route::prefix('admin')->middleware(['auth', 'verified', "role:$admins"])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.users');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->get('/{user}', [AdminUserController::class, 'userShow'])->withTrashed()->name('admin.user.show');
        Route::middleware('role:Super Admin')->put('/{user}', [AdminUserController::class, 'userUpdate'])->withTrashed()->name('admin.user.update');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->put('/{user}/password', [AdminUserController::class, 'userUpdatePassword'])->withTrashed()->name('admin.user.update.password');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->delete('/{user}', [AdminUserController::class, 'userDestroy'])->name('admin.user.destroy');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->put('/{user}/restore', [AdminUserController::class, 'userRestore'])->withTrashed()->name('admin.user.restore');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->delete('/{user}/force-delete', [AdminUserController::class, 'userForceDelete'])->withTrashed()->name('admin.user.force-delete');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('admin.customers');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->get('/{user}', [AdminCustomerController::class, 'customerShow'])->withTrashed()->name('admin.customer.show');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->put('/{user}', [AdminCustomerController::class, 'customerUpdate'])->withTrashed()->name('admin.customer.update');
    });

    Route::prefix('products/exports')->group(function () {
        Route::post('/', [AdminExportController::class, 'export'])->name('admin.products.export');
        Route::get('/check', [AdminExportController::class, 'checkExport'])->name('admin.products.exports.check');
        Route::get('/download', [AdminExportController::class, 'download'])->name('admin.products.export.download');
    });

    Route::prefix('products/imports')->group(function () {
        Route::middleware(['permission:'.PermissionEnum::CREATE->value])->post('/', [AdminImportController::class, 'import'])->name('admin.products.import');
        Route::middleware(['permission:'.PermissionEnum::CREATE->value])->get('/check', [AdminImportController::class, 'checkImport'])->name('admin.products.import.check');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.products');
        Route::get('/categories', [AdminProductController::class, 'searchCategories'])->name('admin.categories.search');
        Route::get('/brands', [AdminProductController::class, 'searchBrands'])->name('admin.brands.search');
        Route::middleware(['permission:'.PermissionEnum::CREATE->value])->get('/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::middleware(['permission:'.PermissionEnum::CREATE->value])->post('/create', [AdminProductController::class, 'store'])->name('admin.products.create');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->get('/{product}', [AdminProductController::class, 'productShow'])->withTrashed()->name('admin.products.show');
        Route::middleware(['permission:'.PermissionEnum::UPDATE->value])->post('/{product}', [AdminProductController::class, 'update'])->withTrashed()->name('admin.products.update');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->delete('/{product}', [AdminProductController::class, 'destroy'])->withTrashed()->name('admin.products.destroy');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->put('/{product}/restore', [AdminProductController::class, 'restore'])->withTrashed()->name('admin.products.restore');
        Route::middleware(['permission:'.PermissionEnum::DELETE->value])->delete('/{product}/force-delete', [AdminProductController::class, 'forceDelete'])->withTrashed()->name('admin.products.force-delete');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('admin.reports');
        Route::post('/', [AdminReportController::class, 'generate'])->name('admin.reports.generate');
        Route::get('/check', [AdminReportController::class, 'checkReport'])->name('admin.reports.check');
        Route::get('/download', [AdminReportController::class, 'download'])->name('admin.reports.download');
    });
});

require __DIR__.'/auth.php';
