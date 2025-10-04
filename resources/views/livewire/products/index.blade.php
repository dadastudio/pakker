<?php

use App\Models\Product;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    public function with(): array
    {
        return [
            'products' => Product::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
  <div class=" mx-auto">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ">
      @foreach ($products as $product)
        <div class="bg-white dark:bg-zinc-800  shadow-sm hover:shadow-md transition-shadow overflow-hidden">
          <div class="aspect-square bg-zinc-100 dark:bg-zinc-700 relative">
            @php
              $media = $product->getFirstMedia('images');

            @endphp

            @if ($media)
              <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
            @else
              <div class="w-full h-full flex items-center justify-center text-zinc-400 dark:text-zinc-500">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            @endif
          </div>

          <div class="p-4">
            <h3 class="font-medium text-zinc-900 dark:text-white truncate">
              {{ $product->name }}
            </h3>

            @if ($product->price)
              <p class="mt-2 text-lg font-bold text-zinc-900 dark:text-white">
                {{ number_format($product->price, 2) }} {{ $product->currency ?? 'PLN' }}
              </p>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    {{-- <div class="mt-8">
      {{ $products->links() }}
    </div> --}}
  </div>
</div>
