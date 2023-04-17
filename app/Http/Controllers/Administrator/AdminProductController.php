<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\BrandsService;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function create(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function productShow(int $id): Response
    {
        return Inertia::render('Administrator/Products/EditProduct', [
            'product' => Product::withTrashed()->findOrFail($id)->load('category:id,name', 'brand:id,name'),
        ]);
    }

    public function store(ProductRequest $request, ProductsService $service): RedirectResponse
    {
        $file = $request->hasFile('image') ? $request->file('image') : null;
        $service->store($request->validated(), $file);

        return redirect()->route('admin.products');
    }

    public function searchCategories(Request $request, CategoriesService $service): Collection|array
    {
        return $service->searchCategories($request->search);
    }

    public function searchBrands(Request $request, BrandsService $service): Collection|array
    {
        return $service->searchBrands($request->search);
    }
}
