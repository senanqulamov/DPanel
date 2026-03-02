<div class="space-y-6">
    <x-card>
        <x-heading-title title="{{ __('My Assigned RFQs') }}" icon="clipboard-document-list" padding="p-5" hover="-" />

        <div class="flex flex-col sm:flex-row gap-3 mt-4 mb-4">
            <div class="flex-1">
                <x-input
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search by title...') }}"
                    icon="magnifying-glass"
                />
            </div>
            <div>
                <select
                    wire:model.live="statusFilter"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200"
                >
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="in_progress">{{ __('In Progress') }}</option>
                    <option value="done">{{ __('Done') }}</option>
                </select>
            </div>
        </div>

        @if($assignments->isEmpty())
            <div class="text-center py-12 border border-dashed border-gray-300 dark:border-slate-600 rounded-xl">
                <x-icon name="clipboard-document-list" class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-3" />
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No assigned RFQs found.') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('Your parent supplier will assign RFQs to you.') }}</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($assignments as $assignment)
                    @php
                        $statusColor = match($assignment->status) {
                            'pending'     => 'yellow',
                            'in_progress' => 'blue',
                            'done'        => 'green',
                            default       => 'gray',
                        };
                    @endphp
                    <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $assignment->request->title }}
                                    </h3>
                                    <x-badge :text="ucfirst(str_replace('_', ' ', $assignment->status))" :color="$statusColor" sm />
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    #{{ $assignment->request->id }}
                                    &bull; {{ __('Buyer') }}: {{ $assignment->request->buyer->name ?? 'N/A' }}
                                    @if($assignment->request->deadline)
                                        &bull; {{ __('Deadline') }}: {{ $assignment->request->deadline->format('M d, Y') }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ __('Assigned by') }}: {{ $assignment->assignedBy->name ?? 'N/A' }}
                                    &bull; {{ $assignment->assigned_at?->diffForHumans() ?? $assignment->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a
                                href="{{ route('supplier.field.rfq.show', $assignment->request) }}"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium transition"
                            >
                                <x-icon name="eye" class="w-3.5 h-3.5" />
                                {{ __('View') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $assignments->links() }}
            </div>
        @endif
    </x-card>
</div>
