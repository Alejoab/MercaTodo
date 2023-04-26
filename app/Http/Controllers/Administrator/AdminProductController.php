<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\BrandsService;
use App\Services\CategoriesService;
use App\Services\ProductsService;
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

    public function productShow(int $id): Response
    {
        return Inertia::render('Administrator/Products/EditProduct', [
            'product' => Product::withTrashed()->findOrFail($id)->load(['category:id,name', 'brand:id,name']),
        ]);
    }

    public function store(ProductRequest $request, ProductsService $service): RedirectResponse
    {
        $service->store($request->validated());

        return redirect()->route('admin.products');
    }

    public function update(ProductRequest $request, int $id, ProductsService $service): RedirectResponse
    {
        $service->update($id, $request->validated());

        return redirect()->route('admin.products.update', $id);
    }

    public function destroy(int $id, ProductsService $service): void
    {
        $service->destroy($id);
    }

    public function restore(int $id, ProductsService $service): void
    {
        $service->restore($id);
    }

    public function forceDelete(int $id, ProductsService $service): void
    {
        $service->forceDelete($id);
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
