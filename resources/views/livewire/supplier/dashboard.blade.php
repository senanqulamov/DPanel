<div>
    <x-card>
        <x-alert color="purple" icon="building-office">
            @lang('Supplier Dashboard')
        </x-alert>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat
                :label="__('Pending Invitations')"
                :value="$pendingInvitations"
                icon="envelope"
                color="blue"
            />
            <x-stat
                :label="__('Active Quotes')"
                :value="$activeQuotes"
                icon="document-text"
                color="yellow"
            />
            <x-stat
                :label="__('Won Quotes')"
                :value="$wonQuotes"
                icon="trophy"
                color="green"
            />
            <x-stat
                :label="__('Total Revenue')"
                :value="'$' . number_format($totalRevenue, 2)"
                icon="currency-dollar"
                color="purple"
            />
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="bell" class="w-5 h-5 inline" /> {{ __('Quick Actions') }}
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('supplier.invitations.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('View Invitations') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('supplier.quotes.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Manage Quotes') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('supplier.messages.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Messages') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="information-circle" class="w-5 h-5 inline" /> {{ __('Supplier Information') }}
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Company') }}:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->company_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Supplier Code') }}:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->supplier_code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Status') }}:</span>
                        <x-badge :text="auth()->user()->supplier_status ?? 'active'" :color="auth()->user()->supplier_status === 'active' ? 'green' : 'yellow'" />
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Currency') }}:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->currency ?? 'USD' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
