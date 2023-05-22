<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Order/ViewCart');

    public function addProductToCart(CartRequest $request, AddProductCart $action): void
    {
        $action->execute($request->user()->id, $request->validated());
    }
}
