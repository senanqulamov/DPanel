<div>
    <x-slide wire="modal" right size="xl" blur="md">
        <x-slot name="title">{{ __('Update Worker: #:id', ['id' => $worker?->id]) }}</x-slot>
        <form id="worker-update-{{ $worker?->id }}" wire:submit="save" class="space-y-6">

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
                    label="{{ __('New Password') }}"
                    type="password"
                    wire:model="password"
                    placeholder="{{ __('Leave blank to keep current password') }}"
                    hint="{{ __('Leave blank to keep current password') }}"
                />

                <x-input
                    label="{{ __('Confirm Password') }}"
                    type="password"
                    wire:model="password_confirmation"
                    placeholder="{{ __('Confirm new password') }}"
                />
            </div>

            <x-button
                type="submit"
                form="worker-update-{{ $worker?->id }}"
                color="primary"
                loading="save"
                icon="check"
            >
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </x-slide>
</div>
