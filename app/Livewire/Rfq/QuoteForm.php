<?php

namespace App\Livewire\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Quote;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuoteForm extends Component
{
    use Alert, WithLogging;

    public Request $request;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public ?string $notes = null;

    public function mount(Request $request): void
    {
        $this->request = $request->load('items.product');

        $this->logPageView('RFQ Quote Form', [
            'request_id' => $this->request->id,
        ]);

        $user = Auth::user();

        if (! $user || $user->id === $this->request->buyer_id) {
            abort(403);
        }

        if ($this->request->status !== 'open' || ($this->request->deadline && $this->request->deadline->isPast())) {
            $this->error(__('This RFQ is not open for quotes.'));
            abort(403);
        }

        foreach ($this->request->items as $item) {
            $this->items[$item->id] = [
                'unit_price' => null,
                'quantity' => $item->quantity,
            ];
        }
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        if (! $user) {
            $this->error(__('You must be logged in to submit a quote.'));

            return;
        }

        $total = 0;

        foreach ($this->items as $requestItemId => $data) {
            if (! isset($data['unit_price']) || $data['unit_price'] === null) {
                continue;
            }

            $subtotal = $data['unit_price'] * ($data['quantity'] ?? 1);
            $total += $subtotal;

            Quote::create([
                'request_id' => $this->request->id,
                'supplier_id' => $user->id,
                'unit_price' => $data['unit_price'],
                'total_price' => $subtotal,
                'notes' => $this->notes,
                'status' => 'submitted',
            ]);
        }

        if ($total === 0) {
            $this->error(__('Please provide at least one unit price to submit a quote.'));

            return;
        }

        $this->logModelAction('create', $this->request, 'Quote submitted for RFQ');

        $this->success(__('Quote submitted successfully.'));

        $this->redirectRoute('rfq.show', $this->request);
    }

    public function render(): View
    {
        return view('livewire.rfq.quote-form');
    }
}
