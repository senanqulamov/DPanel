<?php

use App\Http\Controllers\LocaleController;
use App\Livewire\Logs\Index as LogsIndex;
use App\Livewire\Markets\Index as MarketsIndex;
use App\Livewire\Orders\Index as OrdersIndex;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\User\Profile;
use App\Livewire\Users\Index;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', Index::class)->name('users.index');
    Route::get('/user/profile', Profile::class)->name('user.profile');

    // Products
    Route::get('/products', ProductsIndex::class)->name('products.index');

    // Orders
    Route::get('/orders', OrdersIndex::class)->name('orders.index');

    // Markets
    Route::get('/markets', MarketsIndex::class)->name('markets.index');

    // Logs
    Route::get('/logs', LogsIndex::class)->name('logs.index');

    // Settings
    Route::get('/settings', SettingsIndex::class)->name('settings.index');
});

require __DIR__.'/auth.php';
