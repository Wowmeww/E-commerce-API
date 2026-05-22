<?php

namespace App\Http\Controllers\Api\Cart;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Api\Cart\Cart;

class CartController extends Controller
{


    public function show(Cart $cart)
    {
        $this->authorizeCart($cart);

        return ApiResponse::success(data: $cart->load('items'));
    }

    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $this->authorizeCart($cart);

        $cart->update($request->validated());

        return ApiResponse::success(
            data: $cart->fresh()->load('items'),
            message: 'Cart updated successfully.'
        );
    }

    private function authorizeCart(Cart $cart): void
    {
        abort_unless($cart->user_id === auth()->id(), 403);
    }
}
