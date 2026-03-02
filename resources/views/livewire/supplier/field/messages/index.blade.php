<div class="space-y-6">
    <x-card>
        <x-heading-title title="{{ __('Messages') }}" icon="chat-bubble-left-right" padding="p-5" hover="-" />

        @if(! $supplier)
            <div class="text-center py-12">
                <x-icon name="exclamation-circle" class="w-10 h-10 text-red-400 mx-auto mb-2" />
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No parent supplier linked to your account.') }}</p>
            </div>
        @else
            {{-- Supplier Info --}}
            <div class="flex items-center gap-3 p-3 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 mb-4">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($supplier->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->getDisplayName() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $supplier->email }}</p>
                </div>
            </div>

            {{-- Message Thread --}}
            <div class="h-[420px] overflow-y-auto space-y-3 pr-1 mb-4" id="messages-container">
                @forelse($messages as $msg)
                    @php $isMine = $msg->sender_id === auth()->id(); @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] {{ $isMine ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-gray-100' }} rounded-2xl px-4 py-2.5 shadow-sm">
                            @if(! $isMine)
                                <p class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 mb-1">{{ $msg->sender->name }}</p>
                            @endif
                            <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                            @if($msg->rfq)
                                <p class="text-[10px] mt-1 {{ $isMine ? 'text-indigo-200' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ __('Re') }}: {{ $msg->rfq->title }}
                                </p>
                            @endif
                            <p class="text-[10px] mt-1 {{ $isMine ? 'text-indigo-200' : 'text-gray-400' }} text-right">
                                {{ $msg->created_at->format('M d, H:i') }}
                                @if($msg->read_at && $isMine)
                                    &bull; {{ __('Read') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <x-icon name="chat-bubble-left-right" class="w-10 h-10 text-gray-300 dark:text-slate-600 mx-auto mb-2" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No messages yet. Start the conversation!') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Send Message --}}
            <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                <form wire:submit="sendMessage" class="flex gap-3">
                    <div class="flex-1">
                        <x-textarea
                            wire:model="newMessage"
                            placeholder="{{ __('Type your message to your supplier...') }}"
                            rows="2"
                        />
                    </div>
                    <div class="flex items-end">
                        <x-button type="submit" color="indigo" icon="paper-airplane">
                            {{ __('Send') }}
                        </x-button>
                    </div>
                </form>
            </div>
        @endif
    </x-card>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const scrollToBottom = () => {
            const el = document.getElementById('messages-container');
            if (el) el.scrollTop = el.scrollHeight;
        };
        scrollToBottom();
        Livewire.on('message-sent', scrollToBottom);
    });
</script>
