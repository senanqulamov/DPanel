<div>
    <x-modal wire:model="modal" title="{{ __('Edit Field Worker') }}">
        @if($worker)
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

                <x-slot name="footer">
                    <div class="flex justify-between w-full">
                        <x-button color="red" wire:click="delete" wire:confirm="{{ __('Are you sure you want to delete this worker?') }}">
                            {{ __('Delete') }}
                        </x-button>
                        <div class="flex gap-2">
                            <x-button color="secondary" wire:click="$set('modal', false)">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button type="submit" color="primary">
                                {{ __('Update Worker') }}
                            </x-button>
                        </div>
                    </div>
                </x-slot>
            </form>
        @endif
    </x-modal>
</div>
