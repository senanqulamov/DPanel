<?php

namespace App\Livewire\Categories;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public string $name = '';

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.categories.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
        ];
    }

    public function save(): void
    {
        if (!Auth::user()->hasPermission('create_categories')) {
            $this->error('You do not have permission to create categories.');
            return;
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->error($firstError);
            throw $e;
        }

        $category = new Category();
        $category->name = $this->name;
        $category->save();

        $this->logCreate(Category::class, $category->id, [
            'name' => $category->name,
        ]);

        $this->dispatch('created');
        $this->success(__('Category created successfully.'));

        $this->reset(['name', 'modal']);
    }
}
