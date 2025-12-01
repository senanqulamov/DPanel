<?php

use App\Livewire\Supplier\Dashboard as SupplierDashboard;
use App\Livewire\Supplier\Invitations\Index as InvitationsIndex;
use App\Livewire\Supplier\Quotes\Index as QuotesIndex;
use App\Livewire\Supplier\Quotes\Create as QuotesCreate;
use App\Livewire\Supplier\Messages\Index as MessagesIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Supplier Portal Routes
|--------------------------------------------------------------------------
|
| These routes are for suppliers to view RFQ invitations, submit quotes,
| and manage their interactions with buyers.
|
*/

Route::middleware(['auth', 'supplier', 'can:access_supplier_portal'])->prefix('supplier')->name('supplier.')->group(function () {
    // Supplier Dashboard
    Route::get('/dashboard', SupplierDashboard::class)->name('dashboard');

    // Invitations
    Route::get('/invitations', InvitationsIndex::class)->name('invitations.index')->middleware('can:manage_supplier_invitations');

    // Quotes
    Route::get('/quotes', QuotesIndex::class)->name('quotes.index')->middleware('can:view_quotes');
    Route::get('/quotes/create/{invitation}', QuotesCreate::class)->name('quotes.create')->middleware('can:submit_quotes');

    // Messages
    Route::get('/messages', MessagesIndex::class)->name('messages.index');
});
