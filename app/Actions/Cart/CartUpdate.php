<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class CartUpdate
{

    public function updateCart(int $id, int $quantity): void
    {
        $cart = Cart::query()->find($id);

        $cart->update(['quantity' => $quantity]);
    }
}
