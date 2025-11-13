<?php

namespace App\Livewire\Markets;

use App\Livewire\Traits\Alert;
use App\Models\Market;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert;

    public ?Market $market;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.markets.update');
    }

    #[On('load::market')]
    public function load(Market $market): void
    {
        $this->market = $market;

        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'market.name' => [
                'required',
                'string',
                'max:255'
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

        $this->dispatch('updated');

        $this->resetExcept('market');

        $this->success();
    }
}
