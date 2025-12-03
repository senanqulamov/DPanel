<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" x-data="tallstackui_darkTheme()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <tallstackui:script/>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-dark-bg text-dark-text"
      x-cloak
      x-data="{ name: @js(auth()->user()->name) }"
      x-on:name-updated.window="name = $event.detail.name">
<x-layout>
    <x-slot:top>
        <x-dialog/>
        <x-toast/>
    </x-slot:top>
    <x-slot:header>
        <x-layout.header>
            <x-slot:left>
                <x-lang-switch/>
            </x-slot:left>
            <x-slot:right>
                <x-dropdown>
                    <x-slot:action>
                        <div>
                            <button class="cursor-pointer" x-on:click="show = !show">
                                <span icon="chevron-down" class="text-base font-semibold text-primary-500" x-text="name"></span>
                            </button>
                        </div>
                    </x-slot:action>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown.items icon="user" :text="__('Profile')" :href="route('user.profile')"/>
                        <x-dropdown.items icon="cog" :text="__('Settings')" :href="route('settings.index')"/>
                        <x-dropdown.items icon="finger-print" :text="__('Privacy & Roles')" :href="route('privacy.index')"/>
                        <x-dropdown.items icon="archive-box-arrow-down" :text="__('Archive')" :href="route('logs.index')"/>
                        <x-dropdown.items icon="arrow-left-on-rectangle" :text="__('Logout')" onclick="event.preventDefault(); this.closest('form').submit();" separator/>
                    </form>
                </x-dropdown>
            </x-slot:right>
        </x-layout.header>
    </x-slot:header>
    <x-slot:menu>
        <x-side-bar smart collapsible>
            <x-slot:brand>
                <div class="mt-8 flex items-center justify-center">
                    <img src="{{ asset('/assets/images/JVD.png') }}" width="40" height="40" alt="Brand logo"/>
                </div>
            </x-slot:brand>
            <x-side-bar.item :text="__('Dashboard')" icon="home" :route="route('dashboard')"/>

            @if(auth()->user()->isAdmin())
                <x-side-bar.item :text="__('Buyer Dashboard')" icon="shopping-cart" :route="route('buyer.dashboard')"/>
                <x-side-bar.item :text="__('Seller Dashboard')" icon="shopping-bag" :route="route('seller.dashboard')"/>
                <x-side-bar.item :text="__('Supplier Dashboard')" icon="building-office" :route="route('supplier.dashboard')"/>
            @else
                @if(auth()->user()->isBuyer())
                    <x-side-bar.item :text="__('Buyer Dashboard')" icon="shopping-cart" :route="route('buyer.dashboard')"/>
                @endif
                @if(auth()->user()->isSeller())
                    <x-side-bar.item :text="__('Seller Dashboard')" icon="shopping-bag" :route="route('seller.dashboard')"/>
                @endif
                @if(auth()->user()->isSupplier())
                    <x-side-bar.item :text="__('Supplier Dashboard')" icon="building-office" :route="route('supplier.dashboard')"/>
                @endif
            @endif

            <x-side-bar.item :text="__('Users')" icon="users" :route="route('users.index')"/>
            <x-side-bar.item :text="__('Products')" icon="shopping-cart" :route="route('products.index')"/>
            <x-side-bar.item :text="__('Orders')" icon="queue-list" :route="route('orders.index')"/>
            <x-side-bar.item :text="__('RFQ')" icon="queue-list" :route="route('rfq.index')"/>
            <x-side-bar.item :text="__('Markets')" icon="building-storefront" :route="route('markets.index')"/>
            <x-side-bar.item :text="__('Logs')" icon="clipboard-document-list" :route="route('logs.index')"/>
            {{--                <x-side-bar.item :text="__('Settings')" icon="cog-6-tooth" :route="route('settings.index')" />--}}
            {{--                <x-side-bar.item :text="__('Welcome Page')" icon="arrow-uturn-left" :route="route('welcome')" />--}}
        </x-side-bar>
    </x-slot:menu>
    {{ $slot }}
</x-layout>
@livewireScripts
@stack('scripts')
</body>
</html>
