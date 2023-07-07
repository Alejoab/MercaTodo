<?php

namespace App\Http\Controllers;

use App\Services\Products\BrandsService;
use App\Services\Products\CategoriesService;
use App\Services\Products\ProductsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Shows the home page for the user.
     *
     * @param Request           $request
     * @param ProductsService   $productsService
     * @param CategoriesService $categoriesService
     * @param BrandsService     $brandsService
     *
     * @return Response
     */
    public function index(Request $request, ProductsService $productsService, CategoriesService $categoriesService, BrandsService $brandsService): Response
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brands = $request->get('brand');
        $sort = $request->get('sortBy');

        return Inertia::render('User/Home', [
            'products' => $productsService->listProducts($search, $category, $brands, $sort),
            'categories' => $categoriesService->list(),
            'brands' => $brandsService->brandsByCategory($category),
        ]);
    }
}
