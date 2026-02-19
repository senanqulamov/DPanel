<?php

namespace App\Livewire\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\FieldAssessment;
use App\Models\Request;
use App\Models\User;
use App\Services\PriceAdjustmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use Alert, WithLogging;

    public Request $request;

    public bool $canQuote = false;

    public ?string $statusValue = null;

    // Admin controls
    public ?string $requestTypeValue = null;
    public bool $requiresFieldAssessmentValue = false;
    public ?int $fieldEvaluatorId = null;

    // Price generation for quotes
    public array $targetTotalPrices = [];
    public array $generatedPrices = [];

    public array $availableStatuses = [
        'draft' => 'Draft',
        'open' => 'Open',
        'closed' => 'Closed',
        'awarded' => 'Awarded',
        'cancelled' => 'Cancelled',
    ];

    public array $availableRequestTypes = [
        'internal' => 'Internal',
        'public' => 'Public Tender',
    ];

    public function mount(Request $request): void
    {
        $this->request = $request->load([
            'buyer',
            'items',
            'quotes.supplier',
            'quotes.items.requestItem',
            'quotes.adjustedBy',
            'supplierInvitations.supplier',
            'fieldEvaluator',
            'fieldAssessment',
            'latestFieldAssessment',
            'latestFieldAssessment',
        ]);

        $this->statusValue = $this->request->status;
        $this->requestTypeValue = $this->request->request_type ?? 'internal';
        $this->requiresFieldAssessmentValue = $this->request->requires_field_assessment ?? false;
        $this->fieldEvaluatorId = $this->request->assigned_to_field_evaluator_id;

        $this->logPageView('RFQ Show', [
            'request_id' => $this->request->id,
        ]);

        $user = Auth::user();

        // Check if user has already submitted a quote
        $hasSubmittedQuote = $user && $this->request->quotes()
            ->where('supplier_id', $user->id)
            ->exists();

        $this->canQuote = $user
            && $user->id !== $this->request->buyer_id
            && !$hasSubmittedQuote
            && $this->request->status === 'open'
            && ($this->request->deadline === null || $this->request->deadline->isFuture());
    }

    public function updatedStatusValue($value): void
    {
        if (!$value || !array_key_exists($value, $this->availableStatuses)) {
            $this->error(__('Invalid status selected.'));
            $this->statusValue = $this->request->status; // Reset to current status
            return;
        }

        $oldStatus = $this->request->status;

        // Don't update if it's the same status
        if ($oldStatus === $value) {
            return;
        }

        $this->request->status = $value;
        $this->request->save();

        // Observer will fire RequestStatusChanged event automatically

        $this->logUpdate(
            Request::class,
            $this->request->id,
            [
                'status' => [
                    'old' => $oldStatus,
                    'new' => $value,
                ],
            ]
        );

        $this->success(__('RFQ status updated successfully.'));

        // Refresh the request
        $this->request->refresh();
    }

    public function updatedRequestTypeValue($value): void
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            $this->error(__('Only admins can change request type.'));
            $this->requestTypeValue = $this->request->request_type;
            return;
        }

        if (!in_array($value, ['internal', 'public'])) {
            $this->error(__('Invalid request type.'));
            return;
        }

        $oldType = $this->request->request_type;
        $this->request->request_type = $value;
        $this->request->save();

        $this->logUpdate(
            Request::class,
            $this->request->id,
            [
                'request_type' => [
                    'old' => $oldType,
                    'new' => $value,
                ],
            ]
        );

        $this->success(__('Request type updated successfully.'));
        $this->request->refresh();
    }

    public function updatedRequiresFieldAssessmentValue($value): void
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            $this->error(__('Only admins can change field assessment requirement.'));
            $this->requiresFieldAssessmentValue = $this->request->requires_field_assessment;
            return;
        }

        $this->request->requires_field_assessment = (bool) $value;

        // Update field assessment status
        if ($value) {
            $this->request->field_assessment_status = 'pending';
        } else {
            $this->request->field_assessment_status = 'not_required';
            $this->request->assigned_to_field_evaluator_id = null;
            $this->fieldEvaluatorId = null;
        }

        $this->request->save();

        $this->logUpdate(
            Request::class,
            $this->request->id,
            [
                'requires_field_assessment' => [
                    'old' => !$value,
                    'new' => $value,
                ],
            ]
        );

        $this->success(__('Field assessment requirement updated successfully.'));
        $this->request->refresh();
    }

    public function updatedFieldEvaluatorId($value): void
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            $this->error(__('Only admins can assign field evaluators.'));
            $this->fieldEvaluatorId = $this->request->assigned_to_field_evaluator_id;
            return;
        }

        if (!$this->request->requires_field_assessment) {
            $this->error(__('Field assessment is not required for this request.'));
            return;
        }

        $oldEvaluatorId = $this->request->assigned_to_field_evaluator_id;
        $this->request->assigned_to_field_evaluator_id = $value;

        // Note: Field assessments are now submitted by suppliers themselves
        // We just update the status here, no need to create assessment records
        if ($value) {
            $this->request->field_assessment_status = 'pending';
        }

        $this->request->save();

        $this->logUpdate(
            Request::class,
            $this->request->id,
            [
                'assigned_to_field_evaluator_id' => [
                    'old' => $oldEvaluatorId,
                    'new' => $value,
                ],
            ]
        );

        $this->success(__('Field evaluator assigned successfully.'));
        $this->request->refresh();
    }

    /**
     * Confirm accepting a quote
     */
    public function confirmAcceptQuote(int $quoteId): void
    {
        $quote = \App\Models\Quote::where('id', $quoteId)
            ->where('request_id', $this->request->id)
            ->first();

        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        $this->question(
            __('Are you sure you want to accept this quote? All other quotes will be automatically rejected.'),
            __('Accept Quote?')
        )
            ->confirm(method: 'acceptQuote', params: ['quoteId' => $quoteId])
            ->cancel()
            ->send();
    }

    /**
     * Accept a quote
     */
    public function acceptQuote($params): void
    {
        $quoteId = is_array($params) && isset($params['quoteId']) ? (int)$params['quoteId'] : (int)$params;
        $quote = \App\Models\Quote::where('id', $quoteId)
            ->where('request_id', $this->request->id)
            ->first();

        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        $oldStatus = $quote->status;

        // Update quote status to accepted
        $quote->status = 'accepted';
        $quote->save();

        // Optionally, reject all other quotes for this RFQ
        \App\Models\Quote::where('request_id', $this->request->id)
            ->where('id', '!=', $quoteId)
            ->where('status', '!=', 'rejected')
            ->update(['status' => 'rejected']);

        // Update RFQ status to awarded
        $this->request->status = 'awarded';
        $this->request->save();
        $this->statusValue = 'awarded';

        // Send notification to supplier
        if ($quote->supplier) {
            $quote->supplier->notify(new \App\Notifications\QuoteStatusChanged($quote, $oldStatus, 'accepted'));
        }

        // Notify other suppliers their quotes were rejected
        $otherQuotes = \App\Models\Quote::where('request_id', $this->request->id)
            ->where('id', '!=', $quoteId)
            ->with('supplier')
            ->get();

        foreach ($otherQuotes as $otherQuote) {
            if ($otherQuote->supplier) {
                $otherQuote->supplier->notify(new \App\Notifications\QuoteStatusChanged($otherQuote, $otherQuote->status, 'rejected'));
            }
        }

        $this->logUpdate(
            \App\Models\Quote::class,
            $quote->id,
            [
                'status' => [
                    'old' => $oldStatus,
                    'new' => 'accepted',
                ],
                'action' => 'Quote accepted by buyer',
            ]
        );

        // Record workflow event
        $user = Auth::user();
        try {
            \App\Models\WorkflowEvent::create([
                'eventable_type' => get_class($this->request),
                'eventable_id' => $this->request->id,
                'user_id' => $user?->id,
                'event_type' => 'quote_accepted',
                'from_state' => null,
                'to_state' => null,
                'description' => 'User ' . ($user?->name ?? 'system') . ' accepted quote #' . $quote->id . ' from supplier ' . ($quote->supplier?->name ?? 'unknown'),
                'occurred_at' => now(),
                'metadata' => [
                    'user_id' => $user?->id,
                    'user_name' => $user?->name,
                    'quote_id' => $quote->id,
                    'supplier_id' => $quote->supplier?->id ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            $this->logException($e);
        }

        // Refresh the request
        $this->request->refresh()->load([
            'items',
            'quotes.supplier',
            'quotes.items.requestItem',
        ]);

        $this->success(__('Quote accepted successfully. Other quotes have been rejected.'));
    }

    /**
     * Confirm rejecting a quote
     */
    public function confirmRejectQuote(int $quoteId): void
    {
        $quote = \App\Models\Quote::where('id', $quoteId)
            ->where('request_id', $this->request->id)
            ->first();

        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        $this->question(
            __('Are you sure you want to reject this quote?'),
            __('Reject Quote?')
        )
            ->confirm(method: 'rejectQuote', params: ['quoteId' => $quoteId])
            ->cancel()
            ->send();
    }

    /**
     * Reject a quote
     */
    public function rejectQuote($params): void
    {
        $quoteId = is_array($params) && isset($params['quoteId']) ? (int)$params['quoteId'] : (int)$params;
        $quote = \App\Models\Quote::where('id', $quoteId)
            ->where('request_id', $this->request->id)
            ->first();

        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        $oldStatus = $quote->status;

        // Update quote status to rejected
        $quote->status = 'rejected';
        $quote->save();

        // Send notification to supplier
        if ($quote->supplier) {
            $quote->supplier->notify(new \App\Notifications\QuoteStatusChanged($quote, $oldStatus, 'rejected'));
        }

        $this->logUpdate(
            \App\Models\Quote::class,
            $quote->id,
            [
                'status' => [
                    'old' => $oldStatus,
                    'new' => 'rejected',
                ],
                'action' => 'Quote rejected by buyer',
            ]
        );

        // Record workflow event
        $user = Auth::user();
        try {
            \App\Models\WorkflowEvent::create([
                'eventable_type' => get_class($this->request),
                'eventable_id' => $this->request->id,
                'user_id' => $user?->id,
                'event_type' => 'quote_rejected',
                'from_state' => null,
                'to_state' => null,
                'description' => 'User ' . ($user?->name ?? 'system') . ' rejected quote #' . $quote->id . ' from supplier ' . ($quote->supplier?->name ?? 'unknown'),
                'occurred_at' => now(),
                'metadata' => [
                    'user_id' => $user?->id,
                    'user_name' => $user?->name,
                    'quote_id' => $quote->id,
                    'supplier_id' => $quote->supplier?->id ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            $this->logException($e);
        }

        // Refresh the request
        $this->request->refresh()->load([
            'items',
            'quotes.supplier',
            'quotes.items.requestItem',
        ]);

        $this->success(__('Quote rejected successfully.'));
    }

    /**
     * Generate adjusted prices for a quote based on a target total price
     */
    public function generatePricesForQuote($quoteId): void
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            $this->error(__('Only admins can generate adjusted prices.'));
            return;
        }

        $targetTotal = $this->targetTotalPrices[$quoteId] ?? null;
        if (!$targetTotal || $targetTotal <= 0) {
            $this->error(__('Please enter a valid target total price.'));
            return;
        }

        $quote = $this->request->quotes()->with('items')->find($quoteId);
        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        try {
            // Use the PriceAdjustmentService for precise financial calculations
            $service = new PriceAdjustmentService();

            // Apply 10% variance (±10% randomness)
            $result = $service->adjustQuotePrices($quote, (string) $targetTotal, 10.0);

            $this->logUpdate(
                \App\Models\Quote::class,
                $quoteId,
                [
                    'price_adjustment' => [
                        'original_total' => $result['original_grand_total'],
                        'target_total' => $result['target_grand_total'],
                        'final_total' => $result['final_grand_total'],
                        'items_adjusted' => $result['items_adjusted'],
                        'variance' => $result['variance_applied'],
                    ],
                ]
            );

            $this->success(__('Prices generated and saved successfully! Total: ') . $result['final_grand_total']);

            // Refresh the request to show updated prices
            $this->request->load(['quotes.items', 'quotes.adjustedBy']);

        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            $this->error(__('An error occurred while generating prices: ') . $e->getMessage());

            // Log the error for debugging
            \Log::error('Price adjustment failed', [
                'quote_id' => $quoteId,
                'target_total' => $targetTotal,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Clear adjusted prices for a quote
     */
    public function clearAdjustedPrices($quoteId): void
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            $this->error(__('Only admins can clear adjusted prices.'));
            return;
        }

        $quote = $this->request->quotes()->with('items')->find($quoteId);
        if (!$quote) {
            $this->error(__('Quote not found.'));
            return;
        }

        // Clear all adjusted prices from items
        foreach ($quote->items as $item) {
            $item->new_unit_price = null;
            $item->save();
        }

        // Clear adjusted price data from quote
        $quote->adjusted_total_price = null;
        $quote->adjusted_at = null;
        $quote->adjusted_by = null;
        $quote->save();

        // Clear from component state
        unset($this->generatedPrices[$quoteId]);
        unset($this->targetTotalPrices[$quoteId]);

        $this->logUpdate(
            \App\Models\Quote::class,
            $quoteId,
            [
                'price_adjustment_cleared' => [
                    'cleared_by' => $user->name,
                ],
            ]
        );

        $this->success(__('Adjusted prices cleared successfully.'));

        // Refresh the request
        $this->request->load(['quotes.items', 'quotes.adjustedBy']);
    }

    public function render(): View
    {
        $user = Auth::user();
        $isAdmin = $user && $user->is_admin;

        return view('livewire.rfq.show', [
            'isAdmin' => $isAdmin,
            'fieldEvaluators' => $isAdmin ? User::where('is_active', true)->get() : collect(),
        ]);
    }
}
