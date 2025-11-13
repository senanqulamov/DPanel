<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Alert;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert;

    public ?Product $product;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.products.update');
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
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->product->save();

        $this->dispatch('updated');

        $this->resetExcept('product');

        $this->success();
    }
}
