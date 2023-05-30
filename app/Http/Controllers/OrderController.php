<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Carts\AddProductCart;
use App\Contracts\Actions\Carts\DeleteProductCart;
use App\Contracts\Actions\Orders\CreateOrder;
use App\Http\Requests\CartRequest;
use App\Services\Carts\CartsService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(CartsService $service): Response
    {
        return Inertia::render('Order/ViewCart', [
            'cart' => $service->getCartWithProducts(auth()->id()),
        ]);
    }

    public function addProductToCart(CartRequest $request, AddProductCart $action): void
    {
        $action->execute($request->user()->id, $request->validated());
    }

    public function deleteProductToCart(Request $request, DeleteProductCart $action): void
    {
        $action->execute(auth()->id(), $request->all());
    }

    public function getNumberOfItems(Request $request, CartsService $service): int
    {
        return $request->user() !== null ? $service->getNumberOfItems(auth()->id()) : 0;
    }

    /**
     * @throws Exception
     */
    public function createNewOrder(Request $request, CreateOrder $action, CartsService $service): void
    {
        $userId = $request->user()->id;
        $cart = $service->getValidData($userId);

        $action->execute($userId, $cart);
    }
}
