<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(int $id): Response
    {
        return Inertia::render('User/Product', [
            'product' => Product::with(['brand:id,name'])->findOrFail($id),
        ]);
    }

    public function listProducts(Request $request, ProductsService $service): LengthAwarePaginator
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brands = $request->get('brand');
        $sort = $request->get('sortBy');

        return $service->listProducts($search, $category, $brands, $sort);
    }
}
