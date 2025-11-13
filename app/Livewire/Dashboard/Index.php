<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Traits\WithLogging;
use App\Models\Log;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    use WithLogging;
    public $stats = [];

    public $recentActivity = [];

    public $ordersByStatus = [];

    public $salesByDay = [];

    public $topProducts = [];

    public $recentOrders = [];

    public $userActivity = [];

    public $systemHealth = [];

    public function mount()
    {
        $this->logPageView('Dashboard');
        $this->loadStats();
        $this->loadChartData();
        $this->loadRecentActivity();
        $this->loadSystemHealth();
    }

    protected function loadStats()
    {
        // Get current counts
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalMarkets = Market::count();

        // Get previous period counts for comparison
        $previousUsers = User::where('created_at', '<', now()->subDays(30))->count();
        $previousOrders = Order::where('created_at', '<', now()->subDays(30))->count();
        $previousProducts = Product::where('created_at', '<', now()->subDays(30))->count();

        // Calculate percentage changes
        $usersChange = $previousUsers > 0 ? (($totalUsers - $previousUsers) / $previousUsers) * 100 : 0;
        $ordersChange = $previousOrders > 0 ? (($totalOrders - $previousOrders) / $previousOrders) * 100 : 0;
        $productsChange = $previousProducts > 0 ? (($totalProducts - $previousProducts) / $previousProducts) * 100 : 0;

        // Calculate total revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $previousRevenue = Order::where('status', 'completed')
            ->where('created_at', '<', now()->subDays(30))
            ->sum('total');
        $revenueChange = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        $this->stats = [
            'users' => [
                'count' => $totalUsers,
                'change' => round($usersChange, 1),
                'label' => 'Total Users',
                'icon' => 'users',
                'color' => 'blue',
            ],
            'orders' => [
                'count' => $totalOrders,
                'change' => round($ordersChange, 1),
                'label' => 'Total Orders',
                'icon' => 'shopping-bag',
                'color' => 'green',
            ],
            'products' => [
                'count' => $totalProducts,
                'change' => round($productsChange, 1),
                'label' => 'Total Products',
                'icon' => 'cube',
                'color' => 'purple',
            ],
            'revenue' => [
                'count' => '$'.number_format($totalRevenue, 2),
                'change' => round($revenueChange, 1),
                'label' => 'Total Revenue',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
            ],
            'markets' => [
                'count' => $totalMarkets,
                'change' => 0,
                'label' => 'Total Markets',
                'icon' => 'building-storefront',
                'color' => 'red',
            ],
        ];
    }

    protected function loadChartData()
    {
        // Orders by status
        $this->ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn ($item) => [
                'label' => ucfirst($item->status),
                'value' => $item->count,
            ])
            ->toArray();

        // Sales by day (last 30 days)
        $this->salesByDay = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
            'date' => $item->date,
            'total' => (float) $item->total,
            'count' => $item->count,
        ])
            ->toArray();

        // Top products
        $this->topProducts = Order::select('product_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total) as revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->product?->name ?? 'Unknown',
                'orders' => $item->order_count,
                'revenue' => (float) $item->revenue,
            ])
            ->toArray();

        // User activity over time (last 30 days)
        $this->userActivity = Log::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('type', 'page_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
            'date' => $item->date,
            'count' => $item->count,
        ])
            ->toArray();
    }

    protected function loadRecentActivity()
    {
        // Recent orders
        $this->recentOrders = Order::with(['user', 'product'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'user' => $order->user?->name ?? 'Unknown',
                'product' => $order->product?->name ?? 'Unknown',
                'total' => $order->total,
                'status' => $order->status,
                'created_at' => $order->created_at->diffForHumans(),
            ])
            ->toArray();

        // Recent activity logs
        $this->recentActivity = Log::with('user')
            ->whereIn('type', ['create', 'update', 'delete'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'user' => $log->user?->name ?? 'System',
                'message' => $log->message,
                'type' => $log->type,
                'created_at' => $log->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    protected function loadSystemHealth()
    {
        // Get log statistics
        $totalLogs = Log::count();
        $logsToday = Log::whereDate('created_at', today())->count();
        $errorLogsToday = Log::whereDate('created_at', today())->where('type', 'error')->count();

        // Get active users today
        $activeUsersToday = Log::whereDate('created_at', today())
            ->distinct('user_id')
            ->count('user_id');

        // Calculate system health score
        $healthScore = 100;
        if ($errorLogsToday > 10) {
            $healthScore -= 20;
        }
        if ($activeUsersToday == 0) {
            $healthScore -= 10;
        }

        $this->systemHealth = [
            'score' => $healthScore,
            'totalLogs' => $totalLogs,
            'logsToday' => $logsToday,
            'errorLogsToday' => $errorLogsToday,
            'activeUsersToday' => $activeUsersToday,
            'status' => $healthScore >= 90 ? 'excellent' : ($healthScore >= 70 ? 'good' : 'warning'),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
