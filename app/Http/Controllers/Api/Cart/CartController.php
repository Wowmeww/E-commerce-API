<?php

namespace App\Http\Controllers\Api\Cart;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Api\Cart\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::with('items')
            ->where('user_id', $request->user()->id)
            ->paginate($request->integer('per_page') ?? 15);

        return ApiResponse::success(data: $carts);
    }

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

    public function destroy(Cart $cart)
    {
        $this->authorizeCart($cart);

        $cart->delete();

        return ApiResponse::success(message: 'Cart deleted successfully.');
    }

    private function authorizeCart(Cart $cart): void
    {
        abort_unless($cart->user_id === auth()->id(), 403);
    }
}
