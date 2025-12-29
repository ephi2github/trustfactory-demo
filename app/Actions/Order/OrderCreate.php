<?php

namespace App\Actions\Order;

use App\Mail\StocksLowNotify;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderCreate
{
    public function create()
    {
        $carts = Cart::query()->where('user_id', auth()->id())->get();

        abort_if($carts->isEmpty(), 400, 'Cart is empty.');

        $subtotal = 0.0;
        foreach ($carts as $item) {
            $price = $item->product->price ?? 0;
            $subtotal += $price * $item->quantity;
        }

        $order = Order::query()->create([
            'user_id' => auth()->id(),
            'total' => $subtotal,
            'purchase_date' => now()
        ]);

        $payload = $carts->mapWithKeys(function ($cart) {
            return [
                $cart->product_id => [
                    'quantity' => (int) $cart->quantity,
                    'unit_price' => (float) $cart->product->price,
                ],
            ];
        })->all();

        $order->products()->syncWithoutDetaching($payload);

        foreach ($carts as $item) {
            $item->product->decrement('stock_quantity', $item->quantity);

            if ($item->product->stock_quantity <= 2) {
                Mail::to(config('app.admin.email'))->send(new StocksLowNotify($item->product->id));
            }
        }

        $carts->each->delete();

        return redirect()->route('order.success');
    }
}
