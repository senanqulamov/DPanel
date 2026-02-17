<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Product;
use App\Models\Market;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?Product $product;

    public bool $modal = false;

    public array $productAttributes = [];

    public function render(): View
    {
        $user = Auth::user();
        $markets = $user && $user->isSeller()
            ? Market::where('user_id', $user->id)->orderBy('name')->get()
            : Market::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('livewire.products.update', compact('markets', 'categories'));
    }

    #[On('load::product')]
    public function load(Product $product): void
    {
        $this->product = $product;

        // Load existing attributes
        $this->productAttributes = $this->product->attributes->map(fn($attr) => [
            'id' => $attr->id,
            'name' => $attr->name,
            'value' => $attr->value,
        ])->toArray();

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
            'product.category_id' => [
                'required',
                'exists:categories,id',
            ],
            'product.market_id' => [
                'required',
                'exists:markets,id',
            ],
            'productAttributes.*.name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'productAttributes.*.value' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function addAttribute(): void
    {
        $this->productAttributes[] = [
            'name' => '',
            'value' => '',
        ];
    }

    public function removeAttribute(int $index): void
    {
        unset($this->productAttributes[$index]);
        $this->productAttributes = array_values($this->productAttributes);
    }

    public function save(): void
    {
        // Check permission
        if (!Auth::user()->hasPermission('edit_products')) {
            $this->error('You do not have permission to edit products.');
            return;
        }

        $user = Auth::user();

        if ($user && $user->isSeller()) {
            $originalMarket = Market::where('id', $this->product->getOriginal('market_id'))
                ->where('user_id', $user->id)
                ->exists();

            if (!$originalMarket) {
                $this->error('You can only edit products in your own markets.');
                return;
            }

            $ownsMarket = Market::where('id', $this->product->market_id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$ownsMarket) {
                $this->error('You can only list products in your own markets.');
                return;
            }

            $this->product->supplier_id = $user->id;
        }

        $this->validate();
        $this->product->save();

        // Sync attributes: delete all existing and recreate
        $this->product->attributes()->delete();

        foreach ($this->productAttributes as $index => $attribute) {
            if (!empty($attribute['name']) && !empty($attribute['value'])) {
                $this->product->attributes()->create([
                    'name' => $attribute['name'],
                    'value' => $attribute['value'],
                    'sort_order' => $index,
                ]);
            }
        }

        $this->logUpdate(Product::class, $this->product->id, $this->product->getDirty());


        $this->dispatch('updated');

        $this->resetExcept('product');

        $this->success();
    }
}
