<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Product;
use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?Product $product;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.products.update', [
            'markets' => Market::all(),
        ]);
    }

    #[On('load::product')]
    public function load(Product $product): void
    {
        $this->product = $product;

        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'product.name' => [
                'required',
                'string',
                'max:255'
            ],
            'product.sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($this->product->id),
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
        if (!Auth::user()->hasPermission('edit_products')) {
            $this->error('You do not have permission to edit products.');
            return;
        }

        $this->validate();
        $changes = $this->product->getDirty();

        $this->product->save();
        $this->logUpdate(Product::class, $this->product->id, $changes);


        $this->dispatch('updated');

        $this->resetExcept('product');

        $this->success();
    }
}
