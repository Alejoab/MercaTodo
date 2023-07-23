<?php

namespace App\Http\Controllers\Web\Carts;

use App\Domain\Carts\Contracts\AddProductCart;
use App\Domain\Carts\Contracts\DeleteProductCart;
use App\Domain\Carts\Services\CartsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(CartsService $cartsService): Response
    {
        return Inertia::render('Order/ViewCart', [
            'cart' => $cartsService->getCartWithProducts(auth()->id()),
        ]);
    }

    public function addProductToCart(CartRequest $request, AddProductCart $addProductCartAction): void
    {
        $addProductCartAction->execute($request->user()->id, $request->validated());
    }

    public function deleteProductToCart(Request $request, DeleteProductCart $deleteProductCartAction): void
    {
        $deleteProductCartAction->execute(auth()->id(), $request->all());
    }

    public function getNumberOfItems(Request $request, CartsService $cartsService): int
    {
        return $request->user() !== null ? $cartsService->getNumberOfItems(auth()->id()) : 0;
    }
}
