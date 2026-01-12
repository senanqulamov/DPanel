<div>
    <x-button icon="plus" wire:click="$set('modal', true)">
        {{ __('Add Worker') }}
    </x-button>

    <x-modal wire:model="modal" title="{{ __('Create Field Worker') }}">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
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
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <x-button color="secondary" wire:click="$set('modal', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" color="primary">
                        {{ __('Create Worker') }}
                    </x-button>
                </div>
            </x-slot>
        </form>
    </x-modal>
</div>
