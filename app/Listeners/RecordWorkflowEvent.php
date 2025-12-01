<?php

namespace App\Listeners;

use App\Events\QuoteSubmitted;
use App\Events\RequestStatusChanged;
use App\Events\SlaReminderDue;
use App\Events\SupplierInvited;
use App\Models\WorkflowEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordWorkflowEvent implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the RequestStatusChanged event.
     */
    public function handleRequestStatusChanged(RequestStatusChanged $event): void
    {
        WorkflowEvent::create([
            'eventable_type' => get_class($event->request),
            'eventable_id' => $event->request->id,
            'user_id' => $event->user?->id,
            'event_type' => 'status_changed',
            'from_state' => $event->oldStatus?->value,
            'to_state' => $event->newStatus->value,
            'description' => "Status changed from " . ($event->oldStatus?->label() ?? 'None') . " to {$event->newStatus->label()}",
            'occurred_at' => now(),
            'metadata' => json_encode([
                'user_name' => $event->user?->name,
            ]),
        ]);
    }

    /**
     * Handle the SupplierInvited event.
     */
    public function handleSupplierInvited(SupplierInvited $event): void
    {
        WorkflowEvent::create([
            'eventable_type' => get_class($event->request),
            'eventable_id' => $event->request->id,
            'user_id' => $event->sender?->id,
            'event_type' => 'supplier_invited',
            'from_state' => null,
            'to_state' => null,
            'description' => "Supplier {$event->supplier->name} invited to RFQ",
            'occurred_at' => now(),
            'metadata' => json_encode([
                'supplier_id' => $event->supplier->id,
                'supplier_name' => $event->supplier->name,
                'invitation_id' => $event->invitation->id,
                'sender_name' => $event->sender?->name,
            ]),
        ]);
    }

    /**
     * Handle the QuoteSubmitted event.
     */
    public function handleQuoteSubmitted(QuoteSubmitted $event): void
    {
        WorkflowEvent::create([
            'eventable_type' => get_class($event->request),
            'eventable_id' => $event->request->id,
            'user_id' => $event->supplier->id,
            'event_type' => 'quote_submitted',
            'from_state' => null,
            'to_state' => null,
            'description' => "Quote submitted by supplier {$event->supplier->name}",
            'occurred_at' => now(),
            'metadata' => json_encode([
                'supplier_id' => $event->supplier->id,
                'supplier_name' => $event->supplier->name,
                'quote_id' => $event->quote->id,
                'quote_total' => $event->quote->total_price,
            ]),
        ]);
    }

    /**
     * Handle the SlaReminderDue event.
     */
    public function handleSlaReminderDue(SlaReminderDue $event): void
    {
        WorkflowEvent::create([
            'eventable_type' => get_class($event->request),
            'eventable_id' => $event->request->id,
            'user_id' => null,
            'event_type' => 'sla_reminder',
            'from_state' => null,
            'to_state' => null,
            'description' => "SLA reminder: {$event->daysRemaining} days remaining until deadline",
            'occurred_at' => now(),
            'metadata' => json_encode([
                'days_remaining' => $event->daysRemaining,
                'priority' => $event->priority,
                'deadline' => $event->request->deadline,
            ]),
        ]);
    }
}
