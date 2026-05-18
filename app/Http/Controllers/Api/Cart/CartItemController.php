<?php

namespace App\Http\Controllers\Api\Cart;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\Api\Product\Product;

class CartItemController extends Controller
{
    public function index(Cart $cart)
    {
        $this->authorizeCart($cart);

        return ApiResponse::success(data: $cart->load('items')->items);
    }

    public function store(StoreCartItemRequest $request, Cart $cart)
    {
        $this->authorizeCart($cart);

        $product = Product::findOrFail($request->product_id);

        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
        } else {
            $cartItem = new CartItem([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'product_name' => $product->name,
            ]);
        }

        $cartItem->cart_id = $cart->id;
        $cartItem->price = $product->price;
        $cartItem->total_price = $cartItem->price * $cartItem->quantity;
        $cartItem->product_name = $product->name;
        $cartItem->save();

        return ApiResponse::success(
            data: $cartItem,
            message: 'Cart item added successfully.',
            status: 201
        );
    }

    public function show(Cart $cart, CartItem $item)
    {
        $this->authorizeCartItem($cart, $item);

        return ApiResponse::success(data: $item);
    }

    public function update(UpdateCartItemRequest $request, Cart $cart, CartItem $item)
    {
        $this->authorizeCartItem($cart, $item);

        $item->update([
            'quantity' => $request->quantity,
            'total_price' => $item->price * $request->quantity,
        ]);

        return ApiResponse::success(
            data: $item->fresh(),
            message: 'Cart item updated successfully.'
        );
    }

    public function destroy(Cart $cart, CartItem $item)
    {
        $this->authorizeCartItem($cart, $item);

        $item->delete();

        return ApiResponse::success(message: 'Cart item removed successfully.');
    }

    private function authorizeCart(Cart $cart): void
    {
        abort_unless($cart->user_id === auth()->id(), 403);
    }

    private function authorizeCartItem(Cart $cart, CartItem $item): void
    {
        $this->authorizeCart($cart);

        abort_unless($item->cart_id === $cart->id, 404);
    }
}
