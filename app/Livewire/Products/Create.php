<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Alert;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert;

    public Product $product;

    public bool $modal = false;

    public function mount(): void
    {
        $this->product = new Product;
    }

    public function render(): View
    {
        return view('livewire.products.create');
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
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->product->save();

        $this->dispatch('created');

        $this->reset();
        $this->product = new Product;

        $this->success();
    }
}
