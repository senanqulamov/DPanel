<div>
    <x-card>
        <x-alert color="blue" icon="shopping-cart">
            @lang('Buyer Dashboard')
        </x-alert>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-stat
                :label="__('Open RFQs')"
                :value="$openRfqs"
                icon="document-text"
                color="blue"
            />
            <x-stat
                :label="__('Pending Quotes')"
                :value="$pendingQuotes"
                icon="clock"
                color="yellow"
            />
            <x-stat
                :label="__('Awarded Contracts')"
                :value="$awardedContracts"
                icon="check-circle"
                color="green"
            />
            <x-stat
                :label="__('Total Spend')"
                :value="'$' . number_format($totalSpend, 2)"
                icon="currency-dollar"
                color="purple"
            />
            <x-stat
                :label="__('Active Suppliers')"
                :value="$activeSuppliers"
                icon="user-group"
                color="indigo"
            />
            <x-stat
                :label="__('Completed Orders')"
                :value="$completedOrders"
                icon="clipboard-document-check"
                color="green"
            />
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="bolt" class="w-5 h-5 inline" /> {{ __('Quick Actions') }}
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('rfq.create') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Create New RFQ') }}</span>
                            <x-icon name="plus" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('rfq.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('View All RFQs') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('orders.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Manage Orders') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('products.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Browse Products') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="chart-bar" class="w-5 h-5 inline" /> {{ __('Procurement Overview') }}
                </h3>
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Active RFQs') }}</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $openRfqs }}</span>
                        </div>
                        @if($openRfqs > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($openRfqs / 10) * 100) }}%"></div>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Quotes to Review') }}</span>
                            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingQuotes }}</span>
                        </div>
                        @if($pendingQuotes > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ min(100, ($pendingQuotes / 10) * 100) }}%"></div>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Contracts Awarded') }}</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $awardedContracts }}</span>
                        </div>
                        @if($awardedContracts > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, ($awardedContracts / 10) * 100) }}%"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($openRfqs === 0 && $pendingQuotes === 0)
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
            <x-icon name="light-bulb" class="w-12 h-12 text-blue-500 mx-auto mb-3" />
            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                {{ __('Get Started with Procurement') }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ __('Create your first RFQ to start receiving quotes from suppliers.') }}
            </p>
            <a href="{{ route('rfq.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                {{ __('Create RFQ') }}
            </a>
        </div>
        @endif
    </x-card>
</div>
