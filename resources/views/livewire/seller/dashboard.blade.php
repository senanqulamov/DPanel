<div>
    <x-card>
        <x-alert color="green" icon="shopping-bag">
            @lang('Seller Dashboard')
        </x-alert>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat
                :label="__('Active Markets')"
                :value="$activeMarkets"
                icon="building-storefront"
                color="blue"
            />
            <x-stat
                :label="__('Products Listed')"
                :value="$productsListed"
                icon="cube"
                color="purple"
            />
            <x-stat
                :label="__('Total Sales')"
                :value="$totalSales"
                icon="chart-bar"
                color="green"
            />
            <x-stat
                :label="__('Total Revenue')"
                :value="'$' . number_format($totalRevenue, 2)"
                icon="currency-dollar"
                color="yellow"
            />
            <x-stat
                :label="__('Commission Earned')"
                :value="'$' . number_format($commissionEarned, 2)"
                icon="banknotes"
                color="indigo"
            />
            <x-stat
                :label="__('Pending Orders')"
                :value="$pendingOrders"
                icon="clock"
                color="orange"
            />
            <x-stat
                :label="__('Average Rating')"
                :value="number_format($averageRating, 1) . '/5.0'"
                icon="star"
                color="yellow"
            />
            <x-stat
                :label="__('Verified Seller')"
                :value="auth()->user()->verified_seller ? __('Yes') : __('No')"
                icon="shield-check"
                :color="auth()->user()->verified_seller ? 'green' : 'gray'"
            />
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="bolt" class="w-5 h-5 inline" /> {{ __('Quick Actions') }}
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('products.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Manage Products') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('orders.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('View Orders') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('markets.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Browse Markets') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                    <a href="{{ route('settings.index') }}" class="block p-3 bg-white dark:bg-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Settings') }}</span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-gray-500" />
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <x-icon name="chart-pie" class="w-5 h-5 inline" /> {{ __('Performance Overview') }}
                </h3>
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Products Listed') }}</span>
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $productsListed }}</span>
                        </div>
                        @if($productsListed > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, ($productsListed / 50) * 100) }}%"></div>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Sales') }}</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $totalSales }}</span>
                        </div>
                        @if($totalSales > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, ($totalSales / 100) * 100) }}%"></div>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Seller Rating') }}</span>
                            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($averageRating, 1) }}/5.0</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($averageRating / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($productsListed === 0)
        <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 text-center">
            <x-icon name="light-bulb" class="w-12 h-12 text-green-500 mx-auto mb-3" />
            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                {{ __('Start Selling Today') }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ __('List your first product to start reaching customers in the marketplace.') }}
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                {{ __('Add Product') }}
            </a>
        </div>
        @endif

        @if(!auth()->user()->verified_seller)
        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <x-icon name="exclamation-triangle" class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5" />
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Verify Your Seller Account') }}
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        {{ __('Complete your seller verification to unlock premium features and gain customer trust.') }}
                    </p>
                    <a href="{{ route('settings.index') }}" class="inline-flex items-center text-sm font-medium text-yellow-700 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300">
                        {{ __('Start Verification') }}
                        <x-icon name="arrow-right" class="w-4 h-4 ml-1" />
                    </a>
                </div>
            </div>
        </div>
        @endif
    </x-card>
</div>
