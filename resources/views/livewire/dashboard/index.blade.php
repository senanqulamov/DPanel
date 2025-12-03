<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 mb-8">
        @foreach($stats as $key => $stat)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $stat['color'] === 'blue' ? 'from-blue-500 to-blue-700' : ($stat['color'] === 'green' ? 'from-green-500 to-green-700' : ($stat['color'] === 'purple' ? 'from-purple-500 to-purple-700' : ($stat['color'] === 'yellow' ? 'from-yellow-500 to-yellow-700' : 'from-red-500 to-red-700'))) }} p-6 shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-white/80">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ is_numeric($stat['count']) ? number_format($stat['count']) : $stat['count'] }}</p>
                    @if($stat['change'] != 0)
                    <p class="mt-2 flex items-center text-sm {{ $stat['change'] > 0 ? 'text-white' : 'text-white/70' }}">
                        @if($stat['change'] > 0)
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @endif
                        <span class="font-semibold">{{ abs($stat['change']) }}%</span>
                        <span class="ml-1">vs last period</span>
                    </p>
                    @endif
                </div>
                <div class="rounded-full bg-white/20 p-3 backdrop-blur-sm">
                    <x-icon name="{{ $stat['icon'] }}" class="h-8 w-8 text-white" />
                </div>
            </div>
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-white/10 blur-2xl"></div>
        </div>
        @endforeach
    </div>

    <!-- Dashboard Navigation Cards -->
    @if(auth()->user()->isAdmin() || auth()->user()->isBuyer() || auth()->user()->isSeller() || auth()->user()->isSupplier())
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <x-icon name="squares-2x2" class="w-6 h-6 inline" /> {{ __('Role Dashboards') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(auth()->user()->isAdmin() || auth()->user()->isBuyer())
            <x-dashboard-card
                :title="__('Buyer Dashboard')"
                :description="__('Manage RFQs, review quotes, and track procurement activities.')"
                icon="shopping-cart"
                color="blue"
                :href="route('buyer.dashboard')"
            />
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSeller())
            <x-dashboard-card
                :title="__('Seller Dashboard')"
                :description="__('Manage products, view orders, and track sales performance.')"
                icon="shopping-bag"
                color="green"
                :href="route('seller.dashboard')"
            />
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSupplier())
            <x-dashboard-card
                :title="__('Supplier Dashboard')"
                :description="__('View invitations, submit quotes, and manage supplier activities.')"
                icon="building-office"
                color="purple"
                :href="route('supplier.dashboard')"
            />
            @endif
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
        <!-- Sales Chart - Large -->
        <div class="lg:col-span-2">
            <x-card class="h-full">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Sales Overview</h3>
                        <x-badge color="primary" light>Last 30 Days</x-badge>
                    </div>
                </x-slot>
                <div class="relative h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Orders by Status -->
        <div>
            <x-card class="h-full">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">Orders Status</h3>
                </x-slot>
                <div class="relative h-80 flex items-center justify-center">
                    <canvas id="ordersStatusChart"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
        <!-- User Activity Chart -->
        <div class="lg:col-span-2">
            <x-card class="h-full">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">User Activity</h3>
                        <x-badge color="secondary" light>Page Views</x-badge>
                    </div>
                </x-slot>
                <div class="relative h-64">
                    <canvas id="userActivityChart"></canvas>
                </div>
            </x-card>
        </div>

        <!-- System Health -->
        <div>
            <x-card class="h-full">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">System Health</h3>
                </x-slot>
                <div class="space-y-6">
                    <!-- Health Score -->
                    <div class="text-center">
                        <div class="relative inline-flex h-32 w-32">
                            <svg class="h-full w-full transform -rotate-90">
                                <circle class="text-gray-200 dark:text-gray-700" stroke-width="8" stroke="currentColor" fill="transparent" r="56" cx="64" cy="64"/>
                                <circle class="transition-all duration-1000 ease-out {{ $systemHealth['status'] === 'excellent' ? 'text-green-500' : ($systemHealth['status'] === 'good' ? 'text-blue-500' : 'text-yellow-500') }}" stroke-width="8" stroke-dasharray="{{ 2 * pi() * 56 }}" stroke-dashoffset="{{ 2 * pi() * 56 * (1 - $systemHealth['score'] / 100) }}" stroke-linecap="round" stroke="currentColor" fill="transparent" r="56" cx="64" cy="64"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-3xl font-bold">{{ $systemHealth['score'] }}%</span>
                        </div>
                        <p class="mt-2 text-sm font-medium capitalize {{ $systemHealth['status'] === 'excellent' ? 'text-green-500' : ($systemHealth['status'] === 'good' ? 'text-blue-500' : 'text-yellow-500') }}">
                            {{ ucfirst($systemHealth['status']) }}
                        </p>
                    </div>

                    <!-- Health Metrics -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Active Users Today</span>
                            <span class="font-semibold">{{ $systemHealth['activeUsersToday'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Logs Today</span>
                            <span class="font-semibold">{{ number_format($systemHealth['logsToday']) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Errors Today</span>
                            <span class="font-semibold {{ $systemHealth['errorLogsToday'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                                {{ $systemHealth['errorLogsToday'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Logs</span>
                            <span class="font-semibold">{{ number_format($systemHealth['totalLogs']) }}</span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Third Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Top Products -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold">Top Products</h3>
            </x-slot>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <p class="font-semibold">{{ $product['name'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product['orders'] }} orders</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($product['revenue'], 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No products data available</p>
                @endforelse
            </div>
        </x-card>

        <!-- Recent Orders -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold">Recent Orders</h3>
            </x-slot>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $order['order_number'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $order['user'] }} • {{ $order['product'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $order['created_at'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold">${{ number_format($order['total'], 2) }}</p>
                        <x-badge :color="$order['status'] === 'completed' ? 'green' : ($order['status'] === 'processing' ? 'blue' : 'red')" light sm>
                            {{ ucfirst($order['status']) }}
                        </x-badge>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No recent orders</p>
                @endforelse
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold">Recent Activity</h3>
            </x-slot>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ $activity['type'] === 'create' ? 'bg-green-100 dark:bg-green-900' : ($activity['type'] === 'update' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-red-100 dark:bg-red-900') }} flex items-center justify-center">
                            @if($activity['type'] === 'create')
                            <x-icon name="plus" class="h-4 w-4 text-green-600 dark:text-green-400" />
                            @elseif($activity['type'] === 'update')
                            <x-icon name="pencil" class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            @else
                            <x-icon name="trash" class="h-4 w-4 text-red-600 dark:text-red-400" />
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm">
                            <span class="font-semibold">{{ $activity['user'] }}</span>
                            <span class="text-gray-600 dark:text-gray-400"> {{ $activity['message'] }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['created_at'] }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No recent activity</p>
                @endforelse
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Chart.js default configuration
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;

        // Check if dark mode
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';

        // Sales Chart
        const salesData = @json($salesByDay);
        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Revenue',
                    data: salesData.map(item => item.total),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }, {
                    label: 'Orders',
                    data: salesData.map(item => item.count * 10), // Scaled for visibility
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { color: textColor, font: { size: 12, weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    }
                }
            }
        });

        // Orders Status Chart
        const ordersStatusData = @json($ordersByStatus);
        const ordersStatusChart = new Chart(document.getElementById('ordersStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ordersStatusData.map(item => item.label),
                datasets: [{
                    data: ordersStatusData.map(item => item.value),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(168, 85, 247, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(251, 191, 36)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1
                    }
                },
                cutout: '70%'
            }
        });

        // User Activity Chart
        const userActivityData = @json($userActivity);
        const userActivityChart = new Chart(document.getElementById('userActivityChart'), {
            type: 'bar',
            data: {
                labels: userActivityData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Page Views',
                    data: userActivityData.map(item => item.count),
                    backgroundColor: 'rgba(168, 85, 247, 0.8)',
                    borderColor: 'rgb(168, 85, 247)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(168, 85, 247, 1)'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    }
                }
            }
        });
    </script>
    @endpush
</div>
