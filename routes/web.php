<?php

use App\Http\Controllers\LocaleController;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Logs\Index as LogsIndex;
use App\Livewire\Markets\Index as MarketsIndex;
use App\Livewire\Markets\Show as MarketShow;
use App\Livewire\Orders\Index as OrdersIndex;
use App\Livewire\Orders\Show as OrderShow;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Products\Show as ProductShow;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\User\Profile;
use App\Livewire\Users\Index;
use App\Livewire\Users\Show as UsersShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/users', Index::class)->name('users.index');
    Route::get('/users/{user}', UsersShow::class)->name('users.show');
    Route::get('/user/profile', Profile::class)->name('user.profile');

    // Products
    Route::get('/products', ProductsIndex::class)->name('products.index');
    Route::get('/products/{product}', ProductShow::class)->name('products.show');

    // Orders
    Route::get('/orders', OrdersIndex::class)->name('orders.index');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show');

    // Markets
    Route::get('/markets', MarketsIndex::class)->name('markets.index');
    Route::get('/markets/{market}', MarketShow::class)->name('markets.show');

    // Logs
    Route::get('/logs', LogsIndex::class)->name('logs.index');

    // Settings
    Route::get('/settings', SettingsIndex::class)->name('settings.index');
});

require __DIR__.'/auth.php';
