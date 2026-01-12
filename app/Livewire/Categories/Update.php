<?php

namespace App\Livewire\Categories;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?Category $category = null;

    public bool $modal = false;

    public function mount(Category $category = null): void
    {
        $this->category = $category ?: new Category();
    }

    public function render(): View
    {
        return view('livewire.categories.update');
    }

    #[On('load::category')]
    public function load(Category $category): void
    {
        $this->category = $category;
        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'category.name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->category->id)],
        ];
    }

    public function save(): void
    {
        if (!Auth::user()->hasPermission('edit_categories')) {
            $this->error('You do not have permission to edit categories.');
            return;
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->error($firstError);
            throw $e;
        }

        $this->category->save();

        $this->logUpdate(Category::class, $this->category->id, [
            'name' => $this->category->name,
        ]);

        $this->dispatch('updated');
        $this->success(__('Category updated successfully.'));

        $this->modal = false;
    }
}
