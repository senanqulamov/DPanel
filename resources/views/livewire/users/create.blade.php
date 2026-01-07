<div>
    <x-button :text="__('Create New User')" wire:click="$toggle('modal')" icon="plus" sm />

    <x-modal :title="__('Create New User')" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)" size="2xl" blur="xl">
        <form id="user-create" wire:submit="save" class="space-y-4">

            @if ($errors->any())
                <div class="mb-2 p-3 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r">
                    <div class="text-sm text-red-700 dark:text-red-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4">
                <x-input
                    label="{{ __('Full Name') }} *"
                    x-ref="name"
                    wire:model="name"
                    icon="user"
                    required
                />

                <x-input
                    label="{{ __('Email Address') }} *"
                    wire:model="email"
                    icon="envelope"
                    required
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-password
                        label="{{ __('Password') }} *"
                        wire:model="password"
                        rules
                        generator
                        x-on:generate="$wire.set('password_confirmation', $event.detail.password)"
                        required
                    />
                    <x-password
                        label="{{ __('Confirm Password') }} *"
                        wire:model="password_confirmation"
                        rules
                        required
                    />
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <x-icon name="shield-check" class="w-4 h-4 inline" /> {{ __('User Roles') }}
                    </label>

                    <div class="space-y-2">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-3 p-2 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <input
                                    type="checkbox"
                                    value="{{ $role->id }}"
                                    wire:model="roleIds"
                                    class="rounded border-gray-300 dark:border-gray-700"
                                />
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->display_name ?? ucfirst($role->name) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role->name }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="text-xs text-gray-500 mt-2">
                        {{ __('Select one or more roles. Additional details will be added after creating the user.') }}
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-button type="button" color="secondary" wire:click="$toggle('modal')">{{ __('Cancel') }}</x-button>
                <x-button type="submit" icon="check">{{ __('Create') }}</x-button>
            </div>
        </form>
    </x-modal>
</div>
