<div class="space-y-6">
    {{-- Header --}}
    <x-card class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold mb-1">{{ $request->title }}</h1>
                <p class="text-sm text-indigo-100">
                    #{{ $request->id }}
                    &bull; {{ __('Buyer') }}: {{ $request->buyer->name ?? 'N/A' }}
                    @if($request->deadline)
                        &bull; {{ __('Deadline') }}: {{ $request->deadline->format('M d, Y') }}
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $statusColor = match($assignment->status) {
                        'pending'     => 'bg-yellow-400/20 text-yellow-100 border-yellow-400/40',
                        'in_progress' => 'bg-blue-400/20 text-blue-100 border-blue-400/40',
                        'done'        => 'bg-green-400/20 text-green-100 border-green-400/40',
                        default       => 'bg-gray-400/20 text-gray-100 border-gray-400/40',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                </span>
                <a href="{{ route('supplier.field.rfq.index') }}"
                   class="px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-sm font-medium transition">
                    ← {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-card>

    {{-- Update Status --}}
    <x-card>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <x-icon name="arrow-path" class="w-4 h-4 text-indigo-500" />
            {{ __('Update Your Status') }}
        </h3>
        <div class="flex flex-wrap gap-2">
            @foreach(['pending' => ['label' => __('Pending'), 'color' => 'yellow'], 'in_progress' => ['label' => __('In Progress'), 'color' => 'blue'], 'done' => ['label' => __('Done'), 'color' => 'green']] as $value => $cfg)
                <button
                    wire:click="updateStatus('{{ $value }}')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition border
                        {{ $assignment->status === $value
                            ? 'bg-indigo-600 text-white border-indigo-600'
                            : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-slate-600 hover:border-indigo-400' }}"
                >
                    {{ $cfg['label'] }}
                </button>
            @endforeach
        </div>
    </x-card>

    {{-- RFQ Details --}}
    <x-card>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
            <x-icon name="document-text" class="w-4 h-4 text-indigo-500" />
            {{ __('RFQ Details') }}
        </h3>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Status') }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($request->status) }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Type') }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($request->request_type ?? 'Standard') }}</dd>
            </div>
            @if($request->deadline)
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Deadline') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $request->deadline->format('M d, Y H:i') }}</dd>
                </div>
            @endif
            @if($request->delivery_location)
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Delivery Location') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $request->delivery_location }}</dd>
                </div>
            @endif
        </dl>

        @if($request->description)
            <div class="mb-4">
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Description') }}</dt>
                <dd class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $request->description }}</dd>
            </div>
        @endif

        @if($request->special_instructions)
            <div class="mb-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
                <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 mb-1">{{ __('Special Instructions') }}</p>
                <p class="text-sm text-amber-800 dark:text-amber-300">{{ $request->special_instructions }}</p>
            </div>
        @endif
    </x-card>

    {{-- Items --}}
    @if($request->items->isNotEmpty())
        <x-card>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <x-icon name="list-bullet" class="w-4 h-4 text-indigo-500" />
                {{ __('RFQ Items') }} ({{ $request->items->count() }})
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-slate-700">
                            <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">#</th>
                            <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Product') }}</th>
                            <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Qty') }}</th>
                            <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Unit') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @foreach($request->items as $i => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30">
                                <td class="py-2 px-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="py-2 px-3 text-gray-900 dark:text-gray-100">{{ $item->product_name ?? $item->name ?? 'N/A' }}</td>
                                <td class="py-2 px-3 text-gray-700 dark:text-gray-300">{{ $item->quantity ?? '-' }}</td>
                                <td class="py-2 px-3 text-gray-500">{{ $item->unit ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

    {{-- Field Assessment (if applicable) --}}
    @if($request->latestFieldAssessment)
        <x-card>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <x-icon name="clipboard-document-check" class="w-4 h-4 text-indigo-500" />
                {{ __('Field Assessment') }}
            </h3>
            <div class="p-4 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700">
                <p class="text-sm text-indigo-800 dark:text-indigo-300">
                    {{ __('Recommended Price') }}: <strong>{{ $request->latestFieldAssessment->currency }} {{ number_format($request->latestFieldAssessment->recommended_price, 2) }}</strong>
                </p>
                @if($request->latestFieldAssessment->price_justification)
                    <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-1">{{ $request->latestFieldAssessment->price_justification }}</p>
                @endif
            </div>
        </x-card>
    @endif
</div>
