<?php

namespace App\Livewire\Supplier\FieldAssessment;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\FieldAssessment;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use Alert, WithLogging, WithFileUploads;

    public Request $request;
    public ?FieldAssessment $fieldAssessment = null;

    public array $assessment = [
        'site_location' => '',
        'accessibility_notes' => '',
        'current_condition' => '',
        'technical_feasibility' => '',
        'technical_compliance' => '',
        'estimated_duration' => null,
        'duration_unit' => 'days',
        'recommended_price' => null,
        'currency' => 'AZN',
        'price_justification' => '',
        'risks_identified' => '',
        'mitigation_recommendations' => '',
        'notes' => '',
    ];

    public $photos = [];
    public $latitude;
    public $longitude;

    protected function rules(): array
    {
        return [
            'assessment.site_location' => ['required', 'string', 'max:255'],
            'assessment.accessibility_notes' => ['nullable', 'string'],
            'assessment.current_condition' => ['required', 'string'],
            'assessment.technical_feasibility' => ['required', 'string'],
            'assessment.technical_compliance' => ['nullable', 'string'],
            'assessment.estimated_duration' => ['nullable', 'integer', 'min:1'],
            'assessment.duration_unit' => ['required', 'in:hours,days,weeks'],
            'assessment.recommended_price' => ['required', 'numeric', 'min:0'],
            'assessment.currency' => ['required', 'string'],
            'assessment.price_justification' => ['required', 'string'],
            'assessment.risks_identified' => ['nullable', 'string'],
            'assessment.mitigation_recommendations' => ['nullable', 'string'],
            'assessment.notes' => ['nullable', 'string'],
            'photos.*' => ['nullable', 'image', 'max:5120'], // 5MB max
        ];
    }

    public function mount(Request $request): void
    {
        $user = Auth::user();

        // Ensure request requires field assessment
        if (!$request->requires_field_assessment) {
            $this->error(__('This RFQ does not require field assessment'));
            $this->redirect(route('supplier.rfq.show', $request));
            return;
        }

        // Check if supplier is invited to this RFQ
        $isInvited = $request->supplierInvitations()
            ->where('supplier_id', $user->id)
            ->exists();

        if (!$isInvited) {
            abort(403, 'Unauthorized');
        }

        $this->request = $request->load(['buyer', 'items']);

        // Check if supplier already has a field assessment
        $this->fieldAssessment = FieldAssessment::where('request_id', $request->id)
            ->where('supplier_id', $user->id)
            ->first();

        if ($this->fieldAssessment) {
            // Load existing assessment data
            if ($this->fieldAssessment->canSubmit()) {
                $this->assessment = [
                    'site_location' => $this->fieldAssessment->site_location ?? '',
                    'accessibility_notes' => $this->fieldAssessment->accessibility_notes ?? '',
                    'current_condition' => $this->fieldAssessment->current_condition ?? '',
                    'technical_feasibility' => $this->fieldAssessment->technical_feasibility ?? '',
                    'technical_compliance' => $this->fieldAssessment->technical_compliance ?? '',
                    'estimated_duration' => $this->fieldAssessment->estimated_duration,
                    'duration_unit' => $this->fieldAssessment->duration_unit ?? 'days',
                    'recommended_price' => $this->fieldAssessment->recommended_price,
                    'currency' => $this->fieldAssessment->currency ?? 'AZN',
                    'price_justification' => $this->fieldAssessment->price_justification ?? '',
                    'risks_identified' => $this->fieldAssessment->risks_identified ?? '',
                    'mitigation_recommendations' => $this->fieldAssessment->mitigation_recommendations ?? '',
                    'notes' => $this->fieldAssessment->notes ?? '',
                ];
                $this->latitude = $this->fieldAssessment->latitude;
                $this->longitude = $this->fieldAssessment->longitude;
            } elseif ($this->fieldAssessment->isCompleted()) {
                // Redirect to show page if already submitted
                $this->redirect(route('supplier.field-assessment.show', $request));
                return;
            }
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            $user = Auth::user();

            // Store photos if any
            $photoPaths = [];
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('field-assessments', 'public');
                    $photoPaths[] = $path;
                }
            }

            // Create or update the field assessment
            $data = [
                'request_id' => $this->request->id,
                'supplier_id' => $user->id,
                'site_location' => $this->assessment['site_location'],
                'accessibility_notes' => $this->assessment['accessibility_notes'],
                'current_condition' => $this->assessment['current_condition'],
                'technical_feasibility' => $this->assessment['technical_feasibility'],
                'technical_compliance' => $this->assessment['technical_compliance'],
                'estimated_duration' => $this->assessment['estimated_duration'],
                'duration_unit' => $this->assessment['duration_unit'],
                'recommended_price' => $this->assessment['recommended_price'],
                'currency' => $this->assessment['currency'],
                'price_justification' => $this->assessment['price_justification'],
                'risks_identified' => $this->assessment['risks_identified'],
                'mitigation_recommendations' => $this->assessment['mitigation_recommendations'],
                'notes' => $this->assessment['notes'],
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'photos' => !empty($photoPaths) ? $photoPaths : ($this->fieldAssessment->photos ?? null),
                'status' => 'submitted',
                'started_at' => $this->fieldAssessment?->started_at ?? now(),
                'completed_at' => now(),
                'submitted_at' => now(),
            ];

            if ($this->fieldAssessment) {
                $this->fieldAssessment->update($data);
            } else {
                $this->fieldAssessment = FieldAssessment::create(array_merge($data, [
                    'assigned_at' => now(),
                ]));
            }

            $this->logActivity('create', FieldAssessment::class, $this->fieldAssessment->id, 'Field assessment submitted');

            $this->success(__('Field assessment submitted successfully'));
            $this->redirect(route('supplier.rfq.show', $this->request));

        } catch (\Exception $e) {
            $this->error(__('Failed to submit assessment: ' . $e->getMessage()));
        }
    }

    public function render(): View
    {
        return view('livewire.supplier.field-assessment.create');
    }
}
