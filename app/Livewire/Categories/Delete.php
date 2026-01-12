<?php

namespace App\Livewire\Categories;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class Delete extends Component
{
    use Alert, WithLogging;

    public Category $category;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-button.circle icon="trash" color="red" wire:click="confirm" />
        </div>
        HTML;
    }

    #[Renderless]
    public function confirm(): void
    {
        if (!Auth::check() || !Auth::user()->hasPermission('delete_categories')) {
            $this->error('You do not have permission to delete categories.');
            return;
        }

        // Check if category has products
        if ($this->category->products()->count() > 0) {
            $this->error('Cannot delete category with associated products.');
            return;
        }

        $this->question()
            ->confirm(method: 'delete')
            ->cancel()
            ->send();
    }

    public function delete(): void
    {
        if (!Auth::check() || !Auth::user()->hasPermission('delete_categories')) {
            $this->error('You do not have permission to delete categories.');
            return;
        }

        if ($this->category->products()->count() > 0) {
            $this->error('Cannot delete category with associated products.');
            return;
        }

        $categoryData = [
            'name' => $this->category->name,
        ];
        $categoryId = $this->category->id;

        $this->category->delete();

        $this->logDelete(Category::class, $categoryId, $categoryData);

        $this->dispatch('deleted');
        $this->success();
    }
}
