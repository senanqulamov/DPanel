<div>
    <x-slide wire="modal" right size="lg" blur="md">
        <x-slot name="title">{{ __('Update Category: #:name', ['name' => $category?->name]) }}</x-slot>
        <form id="category-update-{{ $category?->id }}" wire:submit="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <x-input
                    label="{{ __('Name') }}"
                    wire:model.blur="category.name"
                    required
                    hint="{{ __('Product Category Name') }}"
                />
            </div>

            <x-button
                type="submit"
                form="category-update-{{ $category?->id }}"
                color="primary"
                loading="save"
                icon="check"
            >
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </x-slide>
</div>
