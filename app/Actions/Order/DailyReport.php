<?php

namespace App\Actions\Order;

use App\Mail\DailyReport as DailyReportMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class DailyReport
{

    public function dailyReportEmailSend(): void
    {
        $today = now()->toDateString();

        $orders = Order::query()
            ->whereDate('purchase_date', $today)
            ->with('products')
            ->get();

        $products = collect();
        $totalProductsSold = 0;
        $revenue = 0.0;

        foreach ($orders as $order) {
            $revenue += $order->total;

            foreach ($order->products as $product) {
                $quantity = $product->pivot->quantity;
                $totalProductsSold += $quantity;

                $existingProduct = $products->firstWhere('id', $product->id);

                if ($existingProduct) {
                    $existingProduct->total_quantity += $quantity;
                } else {
                    $product->total_quantity = $quantity;
                    $products->push($product);
                }
            }
        }

        Mail::to(config('app.admin.email'))->send(new DailyReportMail($products, $totalProductsSold, $today, $revenue));
    }
}
