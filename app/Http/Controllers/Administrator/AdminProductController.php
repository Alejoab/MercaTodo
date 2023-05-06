<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Products\CreateProduct;
use App\Contracts\Actions\Products\DeleteProduct;
use App\Contracts\Actions\Products\ForceDeleteProduct;
use App\Contracts\Actions\Products\RestoreProduct;
use App\Contracts\Actions\Products\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\Products\BrandsService;
use App\Services\Products\CategoriesService;
use App\Services\Products\ProductsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Products/Index');
    }

    public function create(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function productShow(Product $product): Response
    {
        return Inertia::render('Administrator/Products/EditProduct', [
            'product' => $product->load(['category:id,name', 'brand:id,name']),
        ]);
    }

    public function store(ProductRequest $request, CreateProduct $action): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->route('admin.products');
    }

    public function update(ProductRequest $request, Product $product, UpdateProduct $action): RedirectResponse
    {
        $action->execute($product, $request->validated());

        return redirect()->route('admin.products.update', $product->getKey());
    }

    public function destroy(Product $product, DeleteProduct $action): void
    {
        $action->execute($product);
    }

    public function restore(Product $product, RestoreProduct $action): void
    {
        $action->execute($product);
    }

    public function forceDelete(Product $product, ForceDeleteProduct $action): void
    {
        $action->execute($product);
    }

    public function searchCategories(Request $request, CategoriesService $service): Collection|array
    {
        return $service->searchCategories($request->get('search'));
    }

    public function searchBrands(Request $request, BrandsService $service): Collection|array
    {
        return $service->searchBrands($request->get('search'));
    }

    public function listProducts(Request $request, ProductsService $service): LengthAwarePaginator
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        return $service->listProductsAdmin($search, $category, $brand);
    }
}
