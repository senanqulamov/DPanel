@php
    $items = [
        ['route' => 'seller.dashboard',      'label' => __('Overview'),     'icon' => 'home-modern'],
        ['route' => 'seller.products.index', 'label' => __('Products'),     'icon' => 'cube'],
        ['route' => 'seller.orders.index',   'label' => __('Orders'),       'icon' => 'receipt-percent'],
        ['route' => 'seller.markets.index',  'label' => __('Markets'),      'icon' => 'building-storefront'],
        ['route' => 'seller.logs.index',     'label' => __('Activity Log'), 'icon' => 'clipboard-document-list'],
        ['route' => 'settings.index',        'label' => __('Settings'),     'icon' => 'cog-6-tooth'],
    ];
@endphp

<x-card class="bg-slate-950/60 border-slate-800">
    <div class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            @php $active = request()->routeIs($item['route']); @endphp

            <a href="{{ route($item['route']) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium transition
                      {{ $active
                          ? 'bg-emerald-600 text-white shadow-sm'
                          : 'bg-slate-900/70 text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700' }}">
                <x-icon name="{{ $item['icon'] }}"
                        class="w-4 h-4 {{ $active ? 'text-white' : 'text-emerald-400' }}" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</x-card>
