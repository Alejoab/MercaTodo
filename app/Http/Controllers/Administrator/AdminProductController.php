<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function store(ProductRequest $request, ProductsService $service)
    {
        $service->store($request->validated());

        return redirect()->route('admin.products');
    }

    public function searchCategories(Request $request, ProductsService $service)
    {
        return $service->searchCategories($request->search);
    }

    public function searchBrands(Request $request, ProductsService $service)
    {
        return $service->searchBrands($request->search);
    }
}
