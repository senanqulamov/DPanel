@php
    $items = [
        ['route' => 'buyer.dashboard',      'label' => __('Overview'),     'icon' => 'home-modern'],
        ['route' => 'buyer.rfq.index',      'label' => __('RFQs'),         'icon' => 'document-text'],
        ['route' => 'buyer.products.index', 'label' => __('Products'),     'icon' => 'cube'],
        ['route' => 'buyer.markets.index',  'label' => __('Markets'),      'icon' => 'building-storefront'],
        ['route' => 'buyer.logs.index',     'label' => __('Activity Log'), 'icon' => 'clipboard-document-list'],
        ['route' => 'settings.index',       'label' => __('Settings'),     'icon' => 'cog-6-tooth'],
    ];
@endphp

<x-card class="bg-slate-950/60 border-slate-800">
    <div class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            @php $active = request()->routeIs($item['route']); @endphp

            <a href="{{ route($item['route']) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium transition
                      {{ $active
                          ? 'bg-blue-600 text-white shadow-sm'
                          : 'bg-slate-900/70 text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700' }}">
                <x-icon name="{{ $item['icon'] }}"
                        class="w-4 h-4 {{ $active ? 'text-white' : 'text-blue-400' }}" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</x-card>
