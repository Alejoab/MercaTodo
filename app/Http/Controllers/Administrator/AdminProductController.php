<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
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

    public function store(ProductRequest $request, ProductsService $service): RedirectResponse
    {
        $service->store($request->validated());

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
