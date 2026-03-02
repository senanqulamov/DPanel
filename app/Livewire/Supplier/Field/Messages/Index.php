<?php

namespace App\Livewire\Supplier\Field\Messages;

use App\Livewire\Traits\Alert;
use App\Models\WorkerMessage;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use Alert;

    public string $newMessage = '';

    public function mount(): void
    {
        $this->markMessagesAsRead();
    }

    private function markMessagesAsRead(): void
    {
        $worker = auth()->user();

        // Mark all messages from parent supplier as read
        WorkerMessage::where('receiver_id', $worker->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => ['required', 'string', 'max:2000']]);

        $worker = auth()->user();

        if (! $worker->supplier_id) {
            $this->error(__('No parent supplier found.'));
            return;
        }

        WorkerMessage::create([
            'sender_id'   => $worker->id,
            'receiver_id' => $worker->supplier_id,
            'body'        => $this->newMessage,
        ]);

        $this->reset('newMessage');
        $this->dispatch('message-sent');
    }

    public function render(): View
    {
        $worker = auth()->user();

        $messages = collect();
        if ($worker->supplier_id) {
            $messages = WorkerMessage::query()
                ->conversation($worker->id, $worker->supplier_id)
                ->with(['sender', 'receiver', 'rfq'])
                ->orderBy('created_at')
                ->get();
        }

        $supplier = $worker->supplier;

        return view('livewire.supplier.field.messages.index', [
            'messages' => $messages,
            'supplier' => $supplier,
        ])->layout('layouts.app');
    }
}
