<?php

namespace App\Livewire\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Product;
use App\Models\Request;
use App\Models\RequestItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public Request $request;

    /** Whether this form is in a modal (parity with Products\Create). */
    public bool $modal = false;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function mount(): void
    {
        $this->logPageView('RFQ Create');

        $this->request = new Request;
        $this->request->status = 'draft';
        $this->request->request_type = 'internal'; // Default to internal

        $this->items = [
            $this->makeEmptyItem(),
        ];
    }

    protected function makeEmptyItem(): array
    {
        return [
            'product_id' => null,
            'quantity' => 1,
            'specifications' => null,
        ];
    }

    public function addItem(): void
    {
        $this->items[] = $this->makeEmptyItem();
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        if (empty($this->items)) {
            $this->items[] = $this->makeEmptyItem();
        }
    }

    public function rules(): array
    {
        $user = Auth::user();
        $isAdmin = $user && $user->is_admin;

        $rules = [
            'request.title' => ['required', 'string', 'max:255'],
            'request.description' => ['nullable', 'string'],
            'request.deadline' => ['required', 'date', 'after:today'],
            'request.delivery_location' => ['nullable', 'string', 'max:255'],
            'request.delivery_address' => ['nullable', 'string'],
            'request.special_instructions' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.specifications' => ['nullable', 'string'],
        ];

        // Add buyer_id validation for admins
        if ($isAdmin) {
            $rules['request.buyer_id'] = ['required', 'exists:users,id'];
            $rules['request.request_type'] = ['nullable', 'in:public,internal'];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        if (! $user) {
            $this->error(__('You must be logged in to create an RFQ.'));
            return;
        }

        // Auto-set buyer_id for non-admin users
        if (!$user->is_admin) {
            $this->request->buyer_id = $user->id;
        }

        // Validate that the selected buyer is active
        if ($this->request->buyer_id) {
            $buyer = User::find($this->request->buyer_id);
            if (!$buyer || !$buyer->is_active) {
                $this->error(__('Cannot create RFQ for an inactive buyer.'));
                return;
            }
        }

        $this->request->status = $this->request->status ?: 'draft';
        $this->request->save();

        foreach ($this->items as $item) {
            RequestItem::create([
                'request_id' => $this->request->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'specifications' => $item['specifications'] ?? null,
            ]);
        }

        $this->logCreate(
            Request::class,
            $this->request->id,
            [
                'title' => $this->request->title,
                'deadline' => $this->request->deadline,
                'items_count' => count($this->items),
                'buyer_id' => $this->request->buyer_id,
                'request_type' => $this->request->request_type,
            ]
        );

        $this->dispatch('created', id: $this->request->id);

        $this->reset();
        $this->request = new Request;
        $this->request->status = 'draft';
        $this->request->request_type = 'internal';
        $this->items = [$this->makeEmptyItem()];

        $this->success(__('RFQ created successfully.'));
    }

    public function render(): View
    {
        $user = Auth::user();
        $isAdmin = $user && $user->is_admin;

        return view('livewire.rfq.create', [
            'products' => Product::orderBy('name')->get(),
            'buyers' => $isAdmin ? User::where('is_buyer', true)->where('is_active', true)->orderBy('name')->get() : collect(),
            'isAdmin' => $isAdmin,
        ]);
    }
}
