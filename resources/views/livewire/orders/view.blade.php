<x-modal wire="showDetailModal" size="4xl" blur="xl">
    @if($selectedOrder)
        <x-slot name="title">
            @lang('Order Details') - {{ $selectedOrder->order_number }}
        </x-slot>

        <div class="space-y-4">
            {{-- Order Number and Status --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('Order Number')
                    </label>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedOrder->order_number }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('Status')
                    </label>
                    <x-badge
                        :text="ucfirst($selectedOrder->status)"
                        :color="match($selectedOrder->status) {
                            'processing' => 'blue',
                            'completed' => 'green',
                            'cancelled' => 'red',
                            default => 'gray'
                        }"
                    />
                </div>
            </div>

            {{-- User and Market --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('User')
                    </label>
                    <x-badge text="{{ $selectedOrder->user ? $selectedOrder->user->name : '-' }}" icon="users" position="left"/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('Market')
                    </label>
                    <x-badge text="{{ $selectedOrder->market ? $selectedOrder->market->name : '-' }}" icon="building-storefront" position="left"/>
                </div>
            </div>

            {{-- Order Items --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    @lang('Order Items')
                </label>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                @lang('Product')
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                @lang('Quantity')
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                @lang('Unit Price')
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                @lang('Subtotal')
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($selectedOrder->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->product->name ?? 'Unknown Product' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right">
                                    ${{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                    ${{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    @lang('No items found')
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">
                                @lang('Total')
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100 text-right">
                                ${{ number_format($selectedOrder->total, 2) }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('Created')
                    </label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $selectedOrder->created_at->format('Y-m-d H:i:s') }}
                        <span class="text-gray-500 text-xs">({{ $selectedOrder->created_at->diffForHumans() }})</span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        @lang('Last Updated')
                    </label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $selectedOrder->updated_at->format('Y-m-d H:i:s') }}
                        <span class="text-gray-500 text-xs">({{ $selectedOrder->updated_at->diffForHumans() }})</span>
                    </p>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <x-button color="lime" text="Edit" wire:click="$dispatch('load::order', { 'order' : '{{ $selectedOrder->id }}'})"/>
            <x-button text="Close" wire:click="closeDetailModal"/>
        </x-slot>
    @endif
</x-modal>
