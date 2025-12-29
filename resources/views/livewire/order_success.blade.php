<?php

use App\Actions\Cart\CartUpdate;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Order')]
class extends Component {

}
?>

<div class="max-w-6xl mx-auto py-8 space-y-8 text-zinc-900 dark:text-zinc-100">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">My Order</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div
                class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-800 p-10 text-center bg-white dark:bg-zinc-900">
                <div class="flex justify-center mb-6">
                    <svg class="w-20 h-20 text-green-500 animate-bounce" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <p class="text-sm font-medium">Your order has been submitted successfully</p>

                <div class="mt-4">
                    <flux:button href="{{ url('/') }}" wire:navigate size="sm" variant="primary">
                        Back to Shopping
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
