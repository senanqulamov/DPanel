<?php

namespace App\Livewire\Markets;

use App\Livewire\Traits\Alert;
use App\Models\Market;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    use Alert;

    public Market $market;

    public bool $modal = false;

    public function mount(): void
    {
        $this->market = new Market;
    }

    public function render(): View
    {
        return view('livewire.markets.create');
    }

    public function rules(): array
    {
        return [
            'market.name' => [
                'required',
                'string',
                'max:255',
            ],
            'market.location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'market.image_path' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->market->save();

        $this->dispatch('created');

        $this->reset();
        $this->market = new Market;

        $this->success();
    }
}
