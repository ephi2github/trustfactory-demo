<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;

class CartCreate
{
    public function addCart(Product $product, int $quantity = 1)
    {
        if (auth()->guest()) return redirect()->route('login');

        $cart = Cart::query()->where(['user_id' => auth()->id(), 'product_id' => $product->id])->first();

        if ($cart) {
            $cart->quantity += $quantity;
        } else {
            Cart::query()->create(['user_id' => auth()->id(), 'product_id' => $product->id, 'quantity' => $quantity]);
        }
    }
}
