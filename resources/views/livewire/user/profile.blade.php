<div @updated="$dispatch('name-updated', { name: $event.detail.name })">
    <x-card :header="__('Edit Your Profile')">
        <form id="update-profile" wire:submit="save">
            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Basic Information')</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input label="{{ __('Name') }} *" wire:model="user.name" required />
                        </div>
                        <div>
                            <x-input label="{{ __('Email') }} *" value="{{ $user->email }}" disabled />
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                @if($user->is_supplier || $user->is_seller)
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Roles')</h3>
                    <div class="flex gap-2 flex-wrap">
                        @if($user->is_buyer)
                            <x-badge color="blue" text="Buyer" icon="shopping-cart" position="left" />
                        @endif
                        @if($user->is_seller)
                            <x-badge color="green" text="{{ $user->verified_seller ? 'Verified Seller' : 'Seller' }}" icon="building-storefront" position="left" />
                        @endif
                        @if($user->is_supplier)
                            <x-badge color="purple" text="Supplier ({{ ucfirst($user->supplier_status) }})" icon="cube" position="left" />
                        @endif
                    </div>
                </div>
                @endif

                <!-- Business Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Business Information')</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input label="{{ __('Company Name') }}" wire:model="user.company_name" />
                        </div>
                        <div>
                            <x-input label="{{ __('Phone') }}" wire:model="user.phone" />
                        </div>
                        <div>
                            <x-input label="{{ __('Mobile') }}" wire:model="user.mobile" />
                        </div>
                        <div>
                            <x-input label="{{ __('Website') }}" wire:model="user.website" placeholder="https://example.com" />
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Address')</h3>
                    <div class="space-y-4">
                        <div>
                            <x-input label="{{ __('Address Line 1') }}" wire:model="user.address_line1" />
                        </div>
                        <div>
                            <x-input label="{{ __('Address Line 2') }}" wire:model="user.address_line2" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input label="{{ __('City') }}" wire:model="user.city" />
                            </div>
                            <div>
                                <x-input label="{{ __('State/Province') }}" wire:model="user.state" />
                            </div>
                            <div>
                                <x-input label="{{ __('Postal Code') }}" wire:model="user.postal_code" />
                            </div>
                        </div>
                        <div>
                            <x-input label="{{ __('Country') }}" wire:model="user.country" />
                        </div>
                    </div>
                </div>

                <!-- Password Change -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Change Password')</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-password :label="__('Password')"
                                        :hint="__('The password will only be updated if you set the value of this field')"
                                        wire:model="password"
                                        rules
                                        generator
                                        x-on:generate="$wire.set('password_confirmation', $event.detail.password)" />
                        </div>
                        <div>
                            <x-password :label="__('Confirm password')" wire:model="password_confirmation" rules />
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="update-profile">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-card>
</div>
