<div>
    <x-slide wire="modal" bottom size="lg" blur="md">
        <x-slot name="title">{{ __('Update User: #:id', ['id' => $user?->id]) }}</x-slot>
        <form id="user-update-{{ $user?->id }}" wire:submit="save" class="space-y-6">

            <!-- User Information - Single Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-input
                    label="{{ __('Name') }}"
                    wire:model.blur="user.name"
                    required
                    hint="{{ __('Full name of the user') }}"
                />

                <x-input
                    label="{{ __('Email') }}"
                    wire:model.blur="user.email"
                    required
                    hint="{{ __('User email address') }}"
                />

                <x-password
                    label="{{ __('New Password') }}"
                    hint="{{ __('Leave empty to keep current password') }}"
                    wire:model.blur="password"
                    rules
                    generator
                    x-on:generate="$wire.set('password_confirmation', $event.detail.password)"
                />

                <x-password
                    label="{{ __('Confirm Password') }}"
                    hint="{{ __('Must match new password') }}"
                    wire:model.blur="password_confirmation"
                    rules
                />
            </div>

            <x-button
                type="submit"
                form="user-update-{{ $user?->id }}"
                color="primary"
                loading="save"
                icon="check"
            >
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </x-slide>
</div>
