<?php

namespace App\Livewire\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\FieldAssessment;
use App\Models\Request;
use App\Models\User;
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
