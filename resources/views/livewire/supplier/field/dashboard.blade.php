<div class="space-y-6">
    {{-- Hero --}}
    <x-card class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-icon name="user-circle" class="w-7 h-7" />
                    <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                        {{ __('Field Supplier Dashboard') }}
                    </h1>
                </div>
                <p class="text-sm md:text-base text-indigo-50/90 max-w-xl">
                    {{ __('Welcome back, :name. Manage your assigned RFQs and communicate with your supplier.', ['name' => auth()->user()->name]) }}
                </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full md:w-auto">
                <div class="bg-indigo-900/30 rounded-lg px-3 py-2 border border-indigo-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-indigo-100/70 mb-1">{{ __('Assigned RFQs') }}</p>
                    <p class="text-lg font-semibold">{{ $assignedRfqs }}</p>
                </div>
                <div class="bg-indigo-900/30 rounded-lg px-3 py-2 border border-indigo-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-indigo-100/70 mb-1">{{ __('Unread Messages') }}</p>
                    <p class="text-lg font-semibold">{{ $unreadMessages }}</p>
                </div>
                <div class="bg-indigo-900/30 rounded-lg px-3 py-2 border border-indigo-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-indigo-100/70 mb-1">{{ __('7-Day Activity') }}</p>
                    <p class="text-lg font-semibold">{{ $recentLogs }}</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        {{-- Pending --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-500/10 via-yellow-500/5 to-transparent dark:from-yellow-500/20 backdrop-blur-sm border border-yellow-200/50 dark:border-yellow-500/30 hover:border-yellow-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/20">
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-yellow-600 dark:text-yellow-400 mb-1.5">{{ __('Pending') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingRfqs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="clock" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] text-gray-600 dark:text-gray-400">{{ __('RFQs awaiting your start.') }}</p>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent dark:from-blue-500/20 backdrop-blur-sm border border-blue-200/50 dark:border-blue-500/30 hover:border-blue-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1.5">{{ __('In Progress') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inProgressRfqs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="arrow-path" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] text-gray-600 dark:text-gray-400">{{ __('Currently working on.') }}</p>
            </div>
        </div>

        {{-- Done --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500/10 via-green-500/5 to-transparent dark:from-green-500/20 backdrop-blur-sm border border-green-200/50 dark:border-green-500/30 hover:border-green-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20">
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400 mb-1.5">{{ __('Completed') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $doneRfqs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="check-circle" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] text-gray-600 dark:text-gray-400">{{ __('Finished assessments.') }}</p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/10 via-purple-500/5 to-transparent dark:from-purple-500/20 backdrop-blur-sm border border-purple-200/50 dark:border-purple-500/30 hover:border-purple-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20">
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1.5">{{ __('Unread Messages') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unreadMessages }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="chat-bubble-left-right" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] text-gray-600 dark:text-gray-400">{{ __('Messages from your supplier.') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <x-card class="bg-slate-950/60 border-slate-800">
        <div class="flex items-center gap-2 mb-4">
            <x-icon name="bolt" class="w-5 h-5 text-yellow-400" />
            <h2 class="text-sm font-semibold text-slate-50">{{ __('Quick Actions') }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('supplier.field.rfq.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-indigo-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-500/15 flex items-center justify-center">
                        <x-icon name="clipboard-document-list" class="w-4 h-4 text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('My Assigned RFQs') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('View and manage your assigned requests.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Go to RFQs') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 group-hover:text-indigo-400" />
                </div>
            </a>

            <a href="{{ route('supplier.field.messages.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-purple-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-purple-500/15 flex items-center justify-center">
                        <x-icon name="chat-bubble-left-right" class="w-4 h-4 text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('Messages') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('Chat with your parent supplier.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Open messages') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 group-hover:text-purple-400" />
                </div>
            </a>

            <a href="{{ route('supplier.field.logs.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-gray-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-gray-500/15 flex items-center justify-center">
                        <x-icon name="clock" class="w-4 h-4 text-gray-300" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('Activity Log') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('View your recent activity.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('View logs') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 group-hover:text-gray-200" />
                </div>
            </a>
        </div>
    </x-card>

    {{-- Parent Supplier Info --}}
    @php $parentSupplier = auth()->user()->supplier; @endphp
    @if($parentSupplier)
        <x-card>
            <div class="flex items-center gap-3 mb-2">
                <x-icon name="building-office" class="w-5 h-5 text-indigo-500" />
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Your Parent Supplier') }}</h3>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($parentSupplier->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $parentSupplier->getDisplayName() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $parentSupplier->email }}</p>
                    @if($parentSupplier->phone)
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $parentSupplier->phone }}</p>
                    @endif
                </div>
                <div class="ml-auto">
                    <a href="{{ route('supplier.field.messages.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium transition">
                        <x-icon name="chat-bubble-left-right" class="w-3.5 h-3.5" />
                        {{ __('Message') }}
                    </a>
                </div>
            </div>
        </x-card>
    @endif
</div>
