<?php

namespace App\Livewire\Supplier\Rfq;

use App\Models\Quote;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuoteForm extends Component
{
    public Request $request;

    public $quote = [
        'items' => [],
        'notes' => '',
        'valid_until' => '',
        'terms' => '',
        'total_amount' => 0,
    ];

    public function mount(Request $request): void
    {
        $this->request = $request;

        // Initialize quote items from RFQ items
        foreach ($request->items as $item) {
            $this->quote['items'][] = [
                'request_item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? 'Unknown',
                'quantity' => $item->quantity,
                'unit_price' => 0,
                'total' => 0,
                'notes' => '',
            ];
        }
    }

    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'quote.items')) {
            $this->calculateTotals();
        }
    }

    public function calculateTotals(): void
    {
        $total = 0;
        foreach ($this->quote['items'] as $index => $item) {
            $itemTotal = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            $this->quote['items'][$index]['total'] = $itemTotal;
            $total += $itemTotal;
        }
        $this->quote['total_amount'] = $total;
    }

    public function submitQuote()
    {
        $this->validate([
            'quote.valid_until' => 'required|date|after:today',
            'quote.items.*.unit_price' => 'required|numeric|min:0',
            'quote.total_amount' => 'required|numeric|min:0',
        ]);

        $quote = Quote::create([
            'request_id' => $this->request->id,
            'supplier_id' => auth()->id(),
            'total_amount' => $this->quote['total_amount'],
            'currency' => auth()->user()->currency ?? 'USD',
            'valid_until' => $this->quote['valid_until'],
            'notes' => $this->quote['notes'],
            'terms' => $this->quote['terms'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Save quote items
        foreach ($this->quote['items'] as $item) {
            $quote->items()->create([
                'request_item_id' => $item['request_item_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        session()->flash('success', 'Quote submitted successfully!');
        $this->redirect(route('supplier.quotes.index'));
    }

    public function render(): View
    {
        return view('livewire.supplier.rfq.quote-form')
            ->layout('layouts.app');
    }
}
