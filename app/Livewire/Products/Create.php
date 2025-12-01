<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public Product $product;

    public bool $modal = false;

    public function mount(): void
    {
        $this->product = new Product;
    }

    public function render(): View
    {
        return view('livewire.products.create', [
            'markets' => Market::all(),
        ]);
    }

    public function rules(): array
    {
        return [
            'product.name' => [
                'required',
                'string',
                'max:255',
            ],
            'product.sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku'),
            ],
            'product.price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'product.stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'product.category' => [
                'nullable',
                'string',
                'max:255',
            ],
            'product.market_id' => [
                'required',
                'exists:markets,id',
            ],
        ];
    }

    public function save(): void
    {
        // Check permission
        if (! Auth::user()->hasPermission('create_products')) {
            $this->error('You do not have permission to create products.');

            return;
        }

        $this->validate();

        $this->product->save();
        $this->logCreate(Product::class, $this->product->id, [
            'name' => $this->product->name,
            'sku' => $this->product->sku,
            'price' => $this->product->price,
            'market_id' => $this->product->market_id,
        ]);

        $this->dispatch('created');

        $this->reset();
        $this->product = new Product;

        $this->success();
    }
}
