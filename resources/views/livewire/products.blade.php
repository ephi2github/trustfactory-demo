<?php

use App\Actions\Cart\CartCreate;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.guest')]
#[Title('Products')]
class extends Component {
    use WithPagination;

    public int $perPage = 24;

    public function with(): array
    {
        $products = Product::query()->paginate($this->perPage);

        return [
            'products' => $products,
        ];
    }

    public function money(?float $amount): string
    {
        if ($amount === null) return '—';
        return number_format($amount, 2);
    }

    public function stockBadge(?int $stock): array
    {
        if ($stock === null) {
            return ['label' => 'Unknown', 'class' => 'bg-zinc-500/10 text-zinc-600 dark:text-zinc-300'];
        }

        if ($stock == 0) {
            return ['label' => 'Out of stock', 'class' => 'bg-red-500/10 text-red-600 dark:text-red-300'];
        }

        if ($stock >= 1) {
            return ['label' => "{$stock} left", 'class' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300'];
        }

        return ['label' => 'In stock', 'class' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300'];
    }

    public function addCart(Product $product): void
    {
        app(CartCreate::class)->addCart($product);
    }
}
?>

<div class="max-w-6xl mx-auto py-8 space-y-8 text-zinc-900 dark:text-zinc-100">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($products as $p)
            @php
                $badge = $this->stockBadge(is_null($p->stock_quantity) ? null : (int) $p->stock_quantity);
                $fallback = 'data:image/svg+xml;utf8,' . rawurlencode(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480"><rect width="100%" height="100%" fill="#0B0F19"/><text x="50%" y="50%" fill="#9CA3AF" font-size="22" font-family="Arial" text-anchor="middle" dominant-baseline="middle">No Image</text></svg>'
                );
            @endphp

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900
                        shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="relative aspect-[4/3] bg-zinc-50 dark:bg-zinc-800">
                    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-md {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-3">
                    {{-- top row --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100 truncate">
                                {{ $p->name }}
                            </h3>
                        </div>
                    </div>

                    {{-- description --}}
                    @if(!empty($p->description))
                        <p class="text-sm text-zinc-600 dark:text-zinc-300 line-clamp-1">
                            {{ $p->description }}
                        </p>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            No description.
                        </p>
                    @endif
                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-baseline justify-between">
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Price</span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ $this->money($p->price) }}
                        </span>
                    </div>
                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-baseline justify-end">
                        <flux:button
                            size="xs"
                            variant="ghost"
                            icon="plus"
                            wire:click="addCart({{ $p }})"
                            class="bg-zinc-50 dark:bg-zinc-800 px-3 py-5 cursor-pointer"
                        >
                            Add to Cart
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full rounded-xl border border-dashed border-zinc-300 dark:border-zinc-800 p-10 text-center">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">No products found.</p>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Try clearing filters or changing your
                    search.</p>
                <div class="mt-4">
                    <flux:button size="sm" variant="primary" wire:click="clearFilters">Reset</flux:button>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>
        {{ $products->links() }}
    </div>
</div>
