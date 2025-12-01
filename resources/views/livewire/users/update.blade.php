<div>
    <x-slide wire="modal" right size="6xl" blur="md">
        <x-slot name="title">{{ __('Update User: :name (#:id)', ['name' => $user?->name, 'id' => $user?->id]) }}</x-slot>

        <form id="user-update-{{ $user?->id }}" wire:submit="save">
            <x-tab selected="Basic Information" :border="true">
                <!-- Basic Information Tab -->
                <x-tab.items tab="Basic Information">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Full Name') }} *"
                                wire:model="user.name"
                                icon="user"
                                hint="{{ __('User\'s full name') }}"
                                required
                            />
                            <x-input
                                label="{{ __('Email Address') }} *"
                                wire:model="user.email"
                                icon="envelope"
                                hint="{{ __('Unique email address') }}"
                                required
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-password
                                label="{{ __('New Password') }}"
                                wire:model="password"
                                rules
                                generator
                                x-on:generate="$wire.set('password_confirmation', $event.detail.password)"
                                hint="{{ __('Leave empty to keep current password') }}"
                            />
                            <x-password
                                label="{{ __('Confirm Password') }}"
                                wire:model="password_confirmation"
                                rules
                                hint="{{ __('Must match new password') }}"
                            />
                        </div>

                        <!-- Roles Section -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <x-icon name="shield-check" class="w-4 h-4 inline" /> {{ __('User Roles') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                                    <input
                                        type="checkbox"
                                        wire:model="user.is_buyer"
                                        id="is_buyer_update"
                                        class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 w-5 h-5"
                                    >
                                    <label for="is_buyer_update" class="ml-3 flex-1 cursor-pointer">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Buyer') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Can purchase products') }}</div>
                                    </label>
                                </div>

                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                                    <input
                                        type="checkbox"
                                        wire:model="user.is_seller"
                                        id="is_seller_update"
                                        class="rounded border-gray-300 dark:border-gray-700 text-green-600 shadow-sm focus:ring-green-500 w-5 h-5"
                                    >
                                    <label for="is_seller_update" class="ml-3 flex-1 cursor-pointer">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Seller') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Can sell on platform') }}</div>
                                    </label>
                                </div>

                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition">
                                    <input
                                        type="checkbox"
                                        wire:model="user.is_supplier"
                                        id="is_supplier_update"
                                        class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:ring-purple-500 w-5 h-5"
                                    >
                                    <label for="is_supplier_update" class="ml-3 flex-1 cursor-pointer">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Supplier') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Can supply products') }}</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-tab.items>

                <!-- Business Information Tab -->
                <x-tab.items tab="Business Information">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Company Name') }}"
                                wire:model="user.company_name"
                                icon="building-office"
                                hint="{{ __('Legal business name') }}"
                            />
                            <x-input
                                label="{{ __('Tax ID / VAT') }}"
                                wire:model="user.tax_id"
                                icon="document-text"
                                hint="{{ __('Tax identification number') }}"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-select.styled
                                label="{{ __('Business Type') }}"
                                wire:model="user.business_type"
                                :options="[
                                    ['label' => 'Individual', 'value' => 'Individual'],
                                    ['label' => 'Company', 'value' => 'Company'],
                                    ['label' => 'Corporation', 'value' => 'Corporation'],
                                    ['label' => 'Partnership', 'value' => 'Partnership'],
                                    ['label' => 'LLC', 'value' => 'LLC']
                                ]"
                                select="label:label|value:value"
                            />
                            <x-input
                                label="{{ __('Website') }}"
                                wire:model="user.website"
                                icon="globe-alt"
                                hint="{{ __('Company website URL') }}"
                                placeholder="https://example.com"
                            />
                        </div>

                        <x-textarea
                            label="{{ __('Business Description') }}"
                            wire:model="user.business_description"
                            hint="{{ __('Brief description of business activities') }}"
                            rows="3"
                        />
                    </div>
                </x-tab.items>

                <!-- Contact Information Tab -->
                <x-tab.items tab="Contact Information">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Phone Number') }}"
                                wire:model="user.phone"
                                icon="phone"
                                hint="{{ __('Primary phone number') }}"
                            />
                            <x-input
                                label="{{ __('Mobile Number') }}"
                                wire:model="user.mobile"
                                icon="device-phone-mobile"
                                hint="{{ __('Mobile phone number') }}"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Address Line 1') }}"
                                wire:model="user.address_line1"
                                icon="map-pin"
                                hint="{{ __('Street address') }}"
                            />
                            <x-input
                                label="{{ __('Address Line 2') }}"
                                wire:model="user.address_line2"
                                hint="{{ __('Apartment, suite, etc.') }}"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-input
                                label="{{ __('City') }}"
                                wire:model="user.city"
                            />
                            <x-input
                                label="{{ __('State / Province') }}"
                                wire:model="user.state"
                            />
                            <x-input
                                label="{{ __('Postal Code') }}"
                                wire:model="user.postal_code"
                            />
                        </div>

                        <x-input
                            label="{{ __('Country') }}"
                            wire:model="user.country"
                            icon="globe-americas"
                        />
                    </div>
                </x-tab.items>

                <!-- Supplier Details Tab -->
                <x-tab.items tab="Supplier Details">
                    <div class="space-y-4">
                        @if($user?->is_supplier)
                            <x-alert color="success" class="mb-4">
                                {{ __('This user is currently a supplier.') }}
                            </x-alert>
                        @else
                            <x-alert color="warning" class="mb-4">
                                {{ __('Enable "Supplier" role in Basic Information tab to activate these fields.') }}
                            </x-alert>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Supplier Code') }}"
                                wire:model="user.supplier_code"
                                icon="qr-code"
                                hint="{{ __('Unique supplier identifier') }}"
                                placeholder="SUP-"
                            />
                            <x-input
                                label="{{ __('D-U-N-S Number') }}"
                                wire:model="user.duns_number"
                                icon="identification"
                                hint="{{ __('Dun & Bradstreet number') }}"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input
                                label="{{ __('Ariba Network ID (ANID)') }}"
                                wire:model="user.ariba_network_id"
                                icon="link"
                                hint="{{ __('SAP Ariba Network ID') }}"
                                placeholder="AN"
                            />
                            <x-select.styled
                                label="{{ __('Currency') }}"
                                wire:model="user.currency"
                                :options="[
                                    ['label' => 'USD - US Dollar', 'value' => 'USD'],
                                    ['label' => 'EUR - Euro', 'value' => 'EUR'],
                                    ['label' => 'GBP - British Pound', 'value' => 'GBP'],
                                    ['label' => 'JPY - Japanese Yen', 'value' => 'JPY'],
                                    ['label' => 'CNY - Chinese Yuan', 'value' => 'CNY']
                                ]"
                                select="label:label|value:value"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-number
                                label="{{ __('Credit Limit') }}"
                                wire:model="user.credit_limit"
                                icon="currency-dollar"
                                hint="{{ __('Maximum credit allowed') }}"
                                min="0"
                                step="0.01"
                            />
                            <x-select.styled
                                label="{{ __('Supplier Status') }}"
                                wire:model="user.supplier_status"
                                :options="[
                                    ['label' => 'Active', 'value' => 'active'],
                                    ['label' => 'Pending', 'value' => 'pending'],
                                    ['label' => 'Inactive', 'value' => 'inactive'],
                                    ['label' => 'Blocked', 'value' => 'blocked']
                                ]"
                                select="label:label|value:value"
                            />
                        </div>

                        @if($user?->supplier_approved_at)
                            <x-alert color="info">
                                {{ __('Supplier approved on :date', ['date' => $user->supplier_approved_at->format('M d, Y H:i')]) }}
                            </x-alert>
                        @endif
                    </div>
                </x-tab.items>

                <!-- Seller Details Tab -->
                <x-tab.items tab="Seller Details">
                    <div class="space-y-4">
                        @if($user?->is_seller)
                            <x-alert color="success" class="mb-4">
                                {{ __('This user is currently a seller.') }}
                            </x-alert>
                        @else
                            <x-alert color="warning" class="mb-4">
                                {{ __('Enable "Seller" role in Basic Information tab to activate these fields.') }}
                            </x-alert>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-number
                                label="{{ __('Commission Rate (%)') }}"
                                wire:model="user.commission_rate"
                                icon="percent-badge"
                                hint="{{ __('Percentage commission on sales') }}"
                                min="0"
                                max="100"
                                step="0.01"
                            />

                            <div class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <input
                                    type="checkbox"
                                    wire:model="user.verified_seller"
                                    id="verified_seller_update"
                                    class="rounded border-gray-300 dark:border-gray-700 text-green-600 shadow-sm focus:ring-green-500 w-5 h-5"
                                >
                                <label for="verified_seller_update" class="ml-3 flex-1 cursor-pointer">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Verified Seller') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Mark as verified seller') }}</div>
                                </label>
                            </div>
                        </div>

                        @if($user?->verified_at)
                            <x-alert color="info">
                                {{ __('Seller verified on :date', ['date' => $user->verified_at->format('M d, Y H:i')]) }}
                            </x-alert>
                        @endif
                    </div>
                </x-tab.items>

                <!-- Performance & Notes Tab -->
                <x-tab.items tab="Performance & Notes">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <x-stat
                                :label="__('Rating')"
                                :value="$user?->rating ? number_format($user->rating, 2) . '/5.00' : 'N/A'"
                                icon="star"
                                color="yellow"
                            />
                            <x-stat
                                :label="__('Total Orders')"
                                :value="$user?->total_orders ?? 0"
                                icon="shopping-bag"
                                color="blue"
                            />
                            <x-stat
                                :label="__('Completed')"
                                :value="$user?->completed_orders ?? 0"
                                icon="check-circle"
                                color="green"
                            />
                            <x-stat
                                :label="__('Cancelled')"
                                :value="$user?->cancelled_orders ?? 0"
                                icon="x-circle"
                                color="red"
                            />
                        </div>

                        @if($user?->getSuccessRate())
                            <x-alert color="info">
                                {{ __('Success Rate: :rate%', ['rate' => number_format($user->getSuccessRate(), 2)]) }}
                            </x-alert>
                        @endif

                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <x-icon name="document-text" class="w-4 h-4 inline" /> {{ __('Account Status') }}
                                </label>
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model="user.is_active"
                                        id="is_active_update"
                                        class="rounded border-gray-300 dark:border-gray-700 text-green-600 shadow-sm focus:ring-green-500 w-5 h-5"
                                    >
                                    <label for="is_active_update" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Active Account') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <x-textarea
                            label="{{ __('Internal Notes') }}"
                            wire:model="user.notes"
                            hint="{{ __('Notes visible only to administrators') }}"
                            rows="4"
                        />
                    </div>
                </x-tab.items>
            </x-tab>
        </form>

        <x-slot:footer>
            <div class="flex justify-between items-center w-full">
                <x-button flat label="{{ __('Cancel') }}" wire:click="$toggle('modal')" />
                <x-button
                    type="submit"
                    form="user-update-{{ $user?->id }}"
                    color="primary"
                    icon="check"
                    loading="save"
                >
                    {{ __('Save Changes') }}
                </x-button>
            </div>
        </x-slot:footer>
    </x-slide>
</div>
