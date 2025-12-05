<?php

namespace App\Livewire\Buyer\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Product;
use App\Models\Request;
use App\Models\RequestItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?Request $request = null;

    public bool $modal = false;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function render(): View
    {
        return view('livewire.buyer.rfq.update', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    #[On('buyer::load::rfq')]
    public function load(int $rfq): void
    {
        $request = Request::with(['items.product', 'buyer'])->find($rfq);

        if (! $request) {
            $this->error(__('The requested RFQ could not be found.'));
            $this->modal = false;

            return;
        }

        // Ensure the buyer owns this RFQ
        if ($request->buyer_id !== Auth::id()) {
            $this->error(__('You do not have permission to edit this RFQ.'));
            $this->modal = false;
            return;
        }

        $this->request = $request;

        $this->items = [];
        foreach ($this->request->items as $item) {
            $this->items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'specifications' => $item->specifications,
            ];
        }

        if (empty($this->items)) {
            $this->items[] = [
                'id' => null,
                'product_id' => null,
                'quantity' => 1,
                'specifications' => null,
            ];
        }

        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'request.title' => ['required', 'string', 'max:255'],
            'request.description' => ['nullable', 'string'],
            'request.deadline' => ['required', 'date', 'after:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.specifications' => ['nullable', 'string'],
        ];
    }

    public function addItem(): void
    {
        $this->items[] = [
            'id' => null,
            'product_id' => null,
            'quantity' => 1,
            'specifications' => null,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    protected function normalizeItems(): void
    {
        $normalized = [];

        foreach ($this->items as $item) {
            if (! isset($item['product_id']) || ! $item['product_id']) {
                continue;
            }

            if (! isset($item['quantity']) || $item['quantity'] <= 0) {
                continue;
            }

            $normalized[] = $item;
        }

        $this->items = $normalized;
    }

    protected function syncItems(): void
    {
        if (! $this->request) {
            return;
        }

        $existingIds = $this->request->items()->pluck('id')->all();
        $keptIds = [];

        foreach ($this->items as $itemData) {
            $id = $itemData['id'] ?? null;

            if ($id) {
                $item = $this->request->items()->find($id);
                if (! $item) {
                    continue;
                }
            } else {
                $item = new RequestItem(['request_id' => $this->request->id]);
            }

            $item->product_id = $itemData['product_id'];
            $item->quantity = $itemData['quantity'];
            $item->specifications = $itemData['specifications'] ?? null;
            $item->save();

            $keptIds[] = $item->id;
        }

        // Delete removed items
        $toDelete = array_diff($existingIds, $keptIds);
        if (! empty($toDelete)) {
            RequestItem::whereIn('id', $toDelete)->delete();
        }
    }

    public function save(): void
    {
        if (! $this->request) {
            return;
        }

        // Ensure the buyer owns this RFQ
        if ($this->request->buyer_id !== Auth::id()) {
            $this->error(__('You do not have permission to edit this RFQ.'));
            return;
        }

        $this->normalizeItems();

        if (count($this->items) === 0) {
            $this->addError('items', __('Please add at least one item to the request.'));

            return;
        }

        $this->validate();

        $changes = $this->request->getDirty();
        $this->request->save();

        $this->syncItems();

        $this->logUpdate(Request::class, $this->request->id, $changes);

        $this->dispatch('updated');

        $this->modal = false;

        $this->success(__('RFQ updated successfully.'));
    }
}
