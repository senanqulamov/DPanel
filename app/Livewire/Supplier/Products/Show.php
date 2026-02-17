<?php

namespace App\Livewire\Supplier\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Product $product;

    public function render(): View
    {
        $this->product->load(['market', 'attributes']);

        return view('livewire.supplier.products.show')
            ->layout('layouts.app');
    }
}
