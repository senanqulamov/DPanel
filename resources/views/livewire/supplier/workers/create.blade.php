<div>
    <x-button :text="__('Create New Field Supplier')" wire:click="$toggle('modal')" sm/>

    <x-modal :title="__('Create New Field Supplier')" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)" size="md" blur="xl">
        <form id="worker-create" wire:submit="save" class="space-y-4">
            <x-input
                label="{{ __('Name') }}"
                wire:model="worker.name"
                placeholder="{{ __('Enter worker name') }}"
            />

            <x-input
                label="{{ __('Email') }}"
                type="email"
                wire:model="worker.email"
                placeholder="{{ __('Enter email address') }}"
            />

            <x-input
                label="{{ __('Password') }}"
                type="password"
                wire:model="password"
                placeholder="{{ __('Enter password') }}"
            />

            <x-input
                label="{{ __('Confirm Password') }}"
                type="password"
                wire:model="password_confirmation"
                placeholder="{{ __('Confirm password') }}"
            />
        </form>
        <x-slot:footer>
            <x-button type="submit" form="worker-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
