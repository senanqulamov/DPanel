<?php

use App\Http\Controllers\LocaleController;
use App\Livewire\Buyer\Dashboard as BuyerDashboard;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Logs\Index as LogsIndex;
use App\Livewire\Markets\Index as MarketsIndex;
use App\Livewire\Markets\Show as MarketShow;
use App\Livewire\Orders\Index as OrdersIndex;
use App\Livewire\Orders\Show as OrderShow;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Products\Show as ProductShow;
use App\Livewire\Seller\Dashboard as SellerDashboard;
use App\Livewire\Seller\Markets\Index as SellerMarketsIndex;
use App\Livewire\Seller\Markets\Show as SellerMarketShow;
use App\Livewire\Seller\Products\Index as SellerProductsIndex;
use App\Livewire\Seller\Products\Show as SellerProductShow;
use App\Livewire\Seller\Orders\Index as SellerOrdersIndex;
use App\Livewire\Seller\Orders\Show as SellerOrderShow;
use App\Livewire\Seller\Logs\Index as SellerLogsIndex;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\User\Profile;
use App\Livewire\Users\Index;
use App\Livewire\Users\Show as UsersShow;
use App\Livewire\Rfq\Create as RfqCreate;
use App\Livewire\Rfq\Index as RfqIndex;
use App\Livewire\Rfq\Show as RfqShow;
use App\Livewire\Rfq\QuoteForm as RfqQuoteForm;
use App\Livewire\Privacy\Index as PrivacyIndex;
use App\Livewire\Privacy\Users\Show as PrivacyUserShow;
use App\Livewire\Privacy\Roles\Show as PrivacyRoleShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard')->middleware('can:view_dashboard');

    // Role-based Dashboards
    Route::get('/buyer/dashboard', BuyerDashboard::class)->name('buyer.dashboard')->middleware('can:view_dashboard');
    Route::get('/seller/dashboard', SellerDashboard::class)->name('seller.dashboard')->middleware(['seller', 'can:view_dashboard']);
    Route::get('/seller/markets', SellerMarketsIndex::class)->name('seller.markets.index')->middleware(['seller', 'can:view_markets']);
    Route::get('/seller/markets/{market}', SellerMarketShow::class)->name('seller.markets.show')->middleware(['seller', 'can:view_markets']);
    Route::get('/seller/products', SellerProductsIndex::class)->name('seller.products.index')->middleware(['seller', 'can:view_products']);
    Route::get('/seller/products/{product}', SellerProductShow::class)->name('seller.products.show')->middleware(['seller', 'can:view_products']);
    Route::get('/seller/orders', SellerOrdersIndex::class)->name('seller.orders.index')->middleware(['seller', 'can:view_orders']);
    Route::get('/seller/orders/{order}', SellerOrderShow::class)->name('seller.orders.show')->middleware(['seller', 'can:view_orders']);
    Route::get('/seller/logs', SellerLogsIndex::class)->name('seller.logs.index')->middleware(['seller', 'can:view_logs']);

    Route::get('/users', Index::class)->name('users.index')->middleware('can:view_users');
    Route::get('/users/{user}', UsersShow::class)->name('users.show')->middleware('can:view_users');
    Route::get('/user/profile', Profile::class)->name('user.profile');

    // Products
    Route::get('/products', ProductsIndex::class)->name('products.index')->middleware('can:view_products');
    Route::get('/products/{product}', ProductShow::class)->name('products.show')->middleware('can:view_products');

    // Orders
    Route::get('/orders', OrdersIndex::class)->name('orders.index')->middleware('can:view_orders');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show')->middleware('can:view_orders');

    // Markets
    Route::get('/markets', MarketsIndex::class)->name('markets.index')->middleware('can:view_markets');
    Route::get('/markets/{market}', MarketShow::class)->name('markets.show')->middleware('can:view_markets');

    // Logs
    Route::get('/logs', LogsIndex::class)->name('logs.index')->middleware('can:view_logs');

    // Settings
    Route::get('/settings', SettingsIndex::class)->name('settings.index')->middleware('can:view_settings');

    // Privacy & Roles Management
    Route::get('/privacy', PrivacyIndex::class)->name('privacy.index')->middleware('can:manage_roles');
    Route::get('/privacy/users/{user}', PrivacyUserShow::class)->name('privacy.users.show')->middleware('can:manage_roles');
    Route::get('/privacy/roles/{role}', PrivacyRoleShow::class)->name('privacy.roles.show')->middleware('can:manage_roles');

    // RFQs (buyer-facing)
    Route::get('/rfq', RfqIndex::class)->name('rfq.index')->middleware('can:view_rfqs');
    Route::get('/rfq/create', RfqCreate::class)->name('rfq.create')->middleware('can:create_rfqs');
    Route::get('/rfq/{request}', RfqShow::class)->name('rfq.show')->middleware('can:view_rfqs');
    Route::get('/rfq/{request}/quote', RfqQuoteForm::class)->name('rfq.quote')->middleware('can:submit_quotes');
});

require __DIR__.'/auth.php';
