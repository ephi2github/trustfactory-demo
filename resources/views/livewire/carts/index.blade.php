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
#[Title('My Cart')]
class extends Component {
    use WithPagination;

    public int $perPage = 12;

    public array $qty = [];

    public bool $showDeleteModal = false;
    public ?Cart $cartToRemove = null;

    public function updatedQty($value, $key): void
    {
        $cartId = (int)$key;
        $q = (int)$value;
        if ($q <= 0) {
            $this->remove($cartId);
            return;
        }
        $q = min($q, 999);

        app(CartUpdate::class)->updateCart($cartId, $q);
    }

    public function inc(int $cartId): void
    {
        $current = (int)($this->qty[$cartId] ?? 1);
        $new = min($current + 1, 999);

        $this->qty[$cartId] = $new;
        app(CartUpdate::class)->updateCart($cartId, $new);
    }

    public function dec(int $cartId): void
    {
        $current = (int)($this->qty[$cartId] ?? 1);
        $new = $current === 1 ? $current : $current - 1;

        $this->qty[$cartId] = $new;
        app(CartUpdate::class)->updateCart($cartId, $new);
    }

    public function showRemoveModal(Cart $cart): void
    {
        $this->showDeleteModal = true;
        $this->cartToRemove = $cart;
    }

    public function remove(Cart $cart): void
    {
        unset($this->qty[$cart->id]);

        $cart->delete();
        $this->showDeleteModal = false;

        $this->resetPage();
    }

    public function buy():void
    {
        app(\App\Actions\Order\OrderCreate::class)->create();
    }

    public function with(): array
    {
        $userId = Auth::id();

        $items = Cart::query()
            ->with(['product'])
            ->where('user_id', $userId)
            ->latest('id')
            ->paginate($this->perPage);

        foreach ($items as $item) {
            if (!array_key_exists($item->id, $this->qty)) {
                $this->qty[$item->id] = (int)$item->quantity;
            }
        }

        $subtotal = 0.0;
        foreach ($items as $item) {
            $price = (float)($item->product->price ?? 0);
            $subtotal += $price * (int)$item->quantity;
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
        ];
    }
}
?>

<div class="max-w-6xl mx-auto py-8 space-y-8 text-zinc-900 dark:text-zinc-100">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">My Cart</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Review items you added. Update quantities or remove items anytime.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <flux:button
                href="{{ url('/') }}"
                wire:navigate
                size="sm"
                icon="plus"
            >
                Add product
            </flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            @forelse ($items as $item)
                @php
                    $product = $item->product;
                    $quantity = (int) ($q[$item->id] ?? $item->quantity);
                    $lineTotal = (float) $product->price * $quantity;
                @endphp

                <div
                    class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
                    <div class="flex gap-4 p-4">
                        <div class="w-24 h-24 rounded-lg overflow-hidden bg-zinc-50 dark:bg-zinc-800 shrink-0">
                        </div>

                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold truncate">
                                        {{ $product?->name ?? 'Unknown product' }}
                                    </h3>
                                </div>

                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    icon="trash"
                                    wire:click="showRemoveModal({{$item}})"
                                />
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Price</span>
                                    <div class="font-semibold">{{ number_format($product->price, 2) }}</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Qty</span>

                                    <div
                                        class="inline-flex items-center rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
                                        <button
                                            type="button"
                                            class="px-2 py-1 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800"
                                            wire:click="dec({{ $item->id }})"
                                            aria-label="Decrease quantity"
                                        >
                                            −
                                        </button>

                                        <input
                                            type="number"
                                            min="1"
                                            step="1"
                                            wire:model.blur="qty.{{ $item->id }}"
                                            class="w-14 text-center text-sm border-x border-zinc-200 dark:border-zinc-700 bg-transparent py-1 outline-none"
                                        />

                                        <button
                                            type="button"
                                            class="px-2 py-1 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800"
                                            wire:click="inc({{ $item->id }})"
                                            aria-label="Increase quantity"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>

                                <div class="text-sm text-zinc-600 dark:text-zinc-300 text-right">
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Total</span>
                                    <div class="font-semibold">{{ number_format($lineTotal) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-800 p-10 text-center bg-white dark:bg-zinc-900">
                    <p class="text-sm font-medium">Your cart is empty.</p>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Add some products to get started.
                    </p>

                    <div class="mt-4">
                        <flux:button href="{{ url('/') }}" wire:navigate size="sm" variant="primary" icon="plus">
                            Add product
                        </flux:button>
                    </div>
                </div>
            @endforelse

            <div>
                {{ $items->links() }}
            </div>
        </div>

        <div class="lg:col-span-1">
            <div
                class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold">Summary</h2>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400">Subtotal</span>
                    <span class="font-semibold">{{ number_format((float) $subtotal, 2) }}</span>
                </div>

                <div
                    class="pt-3 border-t border-zinc-100 dark:border-zinc-800 text-xs text-zinc-500 dark:text-zinc-400">
                    Taxes / shipping not included here.
                </div>
                <div class="pt-2">
                    <flux:button size="sm" variant="primary" class="w-full" wire:click="buy()">
                        Checkout
                    </flux:button>
                </div>

                <div class="pt-2">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        class="w-full"
                        href="{{ url('/') }}"
                        wire:navigate
                    >
                        Continue shopping
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    @if ($showDeleteModal && $cartToRemove)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            aria-modal="true"
            role="dialog"
        >
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-lg max-w-md w-full mx-4 p-6 space-y-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                    Delete invoice
                </h2>

                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Are you sure you want to remove this product from your cart?
                </p>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                        {{ $cartToRemove->product->name }}
                </p>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <flux:button
                        type="button"
                        variant="ghost"
                        wire:click="$set('showDeleteModal', false)"
                    >
                        Cancel
                    </flux:button>

                    <flux:button
                        type="button"
                        variant="danger"
                        wire:click="remove({{$cartToRemove}})"
                    >
                        Delete invoice
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
