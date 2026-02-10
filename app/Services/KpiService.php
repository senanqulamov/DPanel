<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Request;
use App\Models\SupplierInvitation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KpiService
{
    /**
     * Calculate Field Evaluator KPI (field supplier/worker supplier)
     * Tracks performance metrics for supplier workers
     */
    public function getFieldEvaluatorKpi(User $worker): array
    {
        if (!$worker->supplier_id) {
            return $this->emptyFieldEvaluatorKpi();
        }

        // Get quotes submitted by this field evaluator
        $quotes = Quote::where('supplier_id', $worker->id)->get();

        return [
            'total_evaluations' => $quotes->count(),
            'accepted_quotes' => $quotes->where('status', 'accepted')->count(),
            'rejected_quotes' => $quotes->where('status', 'rejected')->count(),
            'pending_quotes' => $quotes->whereIn('status', ['submitted', 'under_review', 'pending'])->count(),
            'success_rate' => $quotes->count() > 0
                ? round(($quotes->where('status', 'accepted')->count() / $quotes->count()) * 100, 2)
                : 0,
            'total_value' => $quotes->sum('total_price'),
            'accepted_value' => $quotes->where('status', 'accepted')->sum('total_price'),
            'avg_quote_value' => $quotes->count() > 0
                ? round($quotes->avg('total_price'), 2)
                : 0,
            'supplier_owner' => $worker->supplier?->name ?? 'N/A',
        ];
    }

    /**
     * Calculate Request (RFQ) execution time
     * Measures time from RFQ creation to completion/award
     */
    public function getRfqExecutionTime(Request $request): array
    {
        $createdAt = $request->created_at;
        $completedAt = null;
        $status = $request->status;

        // Determine completion time based on status
        if (in_array($status, ['awarded', 'closed', 'cancelled'])) {
            $completedAt = $request->updated_at;
        }

        $executionTime = $completedAt
            ? $createdAt->diffInHours($completedAt)
            : $createdAt->diffInHours(now());

        $executionDays = round($executionTime / 24, 2);

        // Time to first quote
        $firstQuote = $request->quotes()->oldest()->first();
        $timeToFirstQuote = $firstQuote
            ? $createdAt->diffInHours($firstQuote->created_at)
            : null;

        // Time to award
        $awardedQuote = $request->quotes()->where('status', 'accepted')->first();
        $timeToAward = $awardedQuote
            ? $createdAt->diffInHours($awardedQuote->updated_at)
            : null;

        return [
            'rfq_id' => $request->id,
            'rfq_title' => $request->title,
            'status' => $status,
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
            'completed_at' => $completedAt?->format('Y-m-d H:i:s'),
            'execution_time_hours' => round($executionTime, 2),
            'execution_time_days' => $executionDays,
            'time_to_first_quote_hours' => $timeToFirstQuote ? round($timeToFirstQuote, 2) : null,
            'time_to_award_hours' => $timeToAward ? round($timeToAward, 2) : null,
            'total_quotes_received' => $request->quotes()->count(),
            'is_completed' => in_array($status, ['awarded', 'closed', 'cancelled']),
        ];
    }

    /**
     * Calculate Supplier response speed
     * Measures how quickly suppliers respond to RFQ invitations
     */
    public function getSupplierResponseSpeed(User $supplier): array
    {
        // Get all invitations for this supplier
        $invitations = SupplierInvitation::where('supplier_id', $supplier->id)
            ->whereNotNull('responded_at')
            ->get();

        if ($invitations->isEmpty()) {
            return $this->emptySupplierResponseSpeed($supplier);
        }

        // Calculate response times in hours
        $responseTimes = $invitations->map(function ($invitation) {
            return $invitation->sent_at->diffInHours($invitation->responded_at);
        });

        $avgResponseTime = round($responseTimes->avg(), 2);
        $minResponseTime = round($responseTimes->min(), 2);
        $maxResponseTime = round($responseTimes->max(), 2);

        // Get quotes submitted
        $quotes = Quote::where('supplier_id', $supplier->id)->get();

        // Calculate quote submission speed (from invitation to quote submission)
        $quoteSubmissionTimes = $quotes->map(function ($quote) {
            $invitation = SupplierInvitation::where('request_id', $quote->request_id)
                ->where('supplier_id', $quote->supplier_id)
                ->first();

            if ($invitation && $invitation->sent_at) {
                return $invitation->sent_at->diffInHours($quote->created_at);
            }
            return null;
        })->filter();

        return [
            'supplier_id' => $supplier->id,
            'supplier_name' => $supplier->name,
            'total_invitations' => SupplierInvitation::where('supplier_id', $supplier->id)->count(),
            'total_responses' => $invitations->count(),
            'response_rate' => round(($invitations->count() / max(SupplierInvitation::where('supplier_id', $supplier->id)->count(), 1)) * 100, 2),
            'avg_response_time_hours' => $avgResponseTime,
            'avg_response_time_days' => round($avgResponseTime / 24, 2),
            'min_response_time_hours' => $minResponseTime,
            'max_response_time_hours' => $maxResponseTime,
            'total_quotes_submitted' => $quotes->count(),
            'avg_quote_submission_time_hours' => $quoteSubmissionTimes->isNotEmpty()
                ? round($quoteSubmissionTimes->avg(), 2)
                : null,
            'accepted_quotes' => $quotes->where('status', 'accepted')->count(),
            'win_rate' => $quotes->count() > 0
                ? round(($quotes->where('status', 'accepted')->count() / $quotes->count()) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get aggregate KPI dashboard data
     */
    public function getAggregateKpis(): array
    {
        // Overall RFQ execution metrics
        $rfqs = Request::whereIn('status', ['awarded', 'closed', 'cancelled'])->get();
        $avgExecutionTime = $rfqs->map(function ($rfq) {
            return $rfq->created_at->diffInHours($rfq->updated_at);
        })->avg();

        // Overall supplier response metrics
        $invitations = SupplierInvitation::whereNotNull('responded_at')->get();
        $avgResponseTime = $invitations->map(function ($inv) {
            return $inv->sent_at->diffInHours($inv->responded_at);
        })->avg();

        // Quote metrics
        $totalQuotes = Quote::count();
        $acceptedQuotes = Quote::where('status', 'accepted')->count();

        return [
            'total_rfqs' => Request::count(),
            'completed_rfqs' => $rfqs->count(),
            'avg_rfq_execution_hours' => round($avgExecutionTime ?? 0, 2),
            'avg_rfq_execution_days' => round(($avgExecutionTime ?? 0) / 24, 2),
            'total_invitations' => SupplierInvitation::count(),
            'responded_invitations' => $invitations->count(),
            'avg_supplier_response_hours' => round($avgResponseTime ?? 0, 2),
            'avg_supplier_response_days' => round(($avgResponseTime ?? 0) / 24, 2),
            'total_quotes' => $totalQuotes,
            'accepted_quotes' => $acceptedQuotes,
            'overall_quote_acceptance_rate' => $totalQuotes > 0
                ? round(($acceptedQuotes / $totalQuotes) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get top performing suppliers by response speed
     */
    public function getTopSuppliersByResponseSpeed(int $limit = 10): Collection
    {
        return User::whereHas('roles', fn($q) => $q->where('name', 'supplier'))
            ->where('supplier_status', 'active')
            ->get()
            ->map(function ($supplier) {
                return [
                    'supplier' => $supplier,
                    'metrics' => $this->getSupplierResponseSpeed($supplier),
                ];
            })
            ->sortBy('metrics.avg_response_time_hours')
            ->take($limit)
            ->values();
    }

    /**
     * Get RFQs with longest execution times
     */
    public function getSlowestRfqs(int $limit = 10): Collection
    {
        return Request::whereIn('status', ['awarded', 'closed', 'cancelled'])
            ->get()
            ->map(function ($rfq) {
                return $this->getRfqExecutionTime($rfq);
            })
            ->sortByDesc('execution_time_hours')
            ->take($limit)
            ->values();
    }

    private function emptyFieldEvaluatorKpi(): array
    {
        return [
            'total_evaluations' => 0,
            'accepted_quotes' => 0,
            'rejected_quotes' => 0,
            'pending_quotes' => 0,
            'success_rate' => 0,
            'total_value' => 0,
            'accepted_value' => 0,
            'avg_quote_value' => 0,
            'supplier_owner' => 'N/A',
        ];
    }

    private function emptySupplierResponseSpeed(User $supplier): array
    {
        return [
            'supplier_id' => $supplier->id,
            'supplier_name' => $supplier->name,
            'total_invitations' => 0,
            'total_responses' => 0,
            'response_rate' => 0,
            'avg_response_time_hours' => 0,
            'avg_response_time_days' => 0,
            'min_response_time_hours' => 0,
            'max_response_time_hours' => 0,
            'total_quotes_submitted' => 0,
            'avg_quote_submission_time_hours' => 0,
            'accepted_quotes' => 0,
            'win_rate' => 0,
        ];
    }

    /**
     * Calculate Procurement KPIs for a given period
     */
    public function calculateProcurementKpis(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Request::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalRfqs = $query->count();
        $activeRfqs = (clone $query)->whereIn('status', ['open', 'under_review'])->count();
        $completedRfqs = (clone $query)->whereIn('status', ['awarded', 'closed'])->count();

        // Calculate average processing time
        $completed = (clone $query)->whereIn('status', ['awarded', 'closed', 'cancelled'])->get();
        $avgProcessingTime = $completed->map(function ($rfq) {
            return $rfq->created_at->diffInDays($rfq->updated_at);
        })->avg() ?? 0;

        // Calculate average response time (hours from RFQ creation to first quote)
        $rfqsWithQuotes = (clone $query)->has('quotes')->with('quotes')->get();
        $avgResponseTime = $rfqsWithQuotes->map(function ($rfq) {
            $firstQuote = $rfq->quotes->sortBy('created_at')->first();
            return $firstQuote ? $rfq->created_at->diffInHours($firstQuote->created_at) : null;
        })->filter()->avg() ?? 0;

        // On-time completion rate (completed before deadline)
        $onTimeCount = $completed->filter(function ($rfq) {
            return $rfq->deadline && $rfq->updated_at <= $rfq->deadline;
        })->count();
        $onTimeRate = $completed->count() > 0 ? ($onTimeCount / $completed->count()) * 100 : 0;

        return [
            'totalRfqs' => $totalRfqs,
            'activeRfqs' => $activeRfqs,
            'completedRfqs' => $completedRfqs,
            'avgProcessingTime' => round($avgProcessingTime, 2),
            'avgResponseTime' => round($avgResponseTime, 2),
            'onTimeRate' => round($onTimeRate, 2),
        ];
    }

    /**
     * Calculate Supplier KPIs for a given period
     */
    public function calculateSupplierKpis(?string $startDate = null, ?string $endDate = null): array
    {
        $totalSuppliers = User::where('is_supplier', true)->count();
        $activeSuppliers = User::where('is_supplier', true)->where('is_active', true)->count();
        $publicTenderEligible = User::where('is_supplier', true)
            ->where('is_public_tender_eligible', true)
            ->count();

        $invitationsQuery = SupplierInvitation::query();
        if ($startDate) {
            $invitationsQuery->whereDate('sent_at', '>=', $startDate);
        }
        if ($endDate) {
            $invitationsQuery->whereDate('sent_at', '<=', $endDate);
        }

        $invitations = $invitationsQuery->get();
        $respondedInvitations = $invitations->whereNotNull('responded_at');

        $avgResponseTime = $respondedInvitations->map(function ($inv) {
            return $inv->sent_at->diffInHours($inv->responded_at);
        })->avg() ?? 0;

        $responseRate = $invitations->count() > 0
            ? ($respondedInvitations->count() / $invitations->count()) * 100
            : 0;

        return [
            'totalSuppliers' => $totalSuppliers,
            'activeSuppliers' => $activeSuppliers,
            'publicTenderEligible' => $publicTenderEligible,
            'avgResponseTime' => round($avgResponseTime, 2),
            'responseRate' => round($responseRate, 2),
            'onTimeDeliveryRate' => 0, // TODO: Implement when delivery tracking is added
        ];
    }

    /**
     * Calculate Quote KPIs for a given period
     */
    public function calculateQuoteKpis(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Quote::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalQuotes = $query->count();
        $submittedQuotes = (clone $query)->where('status', 'submitted')->count();
        $acceptedQuotes = (clone $query)->where('status', 'accepted')->count();

        $avgQuotesPerRfq = Request::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->withCount('quotes')
            ->get()
            ->avg('quotes_count') ?? 0;

        $avgQuoteValue = (clone $query)->avg('total_amount') ?? 0;
        $totalQuoteValue = (clone $query)->sum('total_amount') ?? 0;
        $acceptanceRate = $totalQuotes > 0 ? ($acceptedQuotes / $totalQuotes) * 100 : 0;

        return [
            'totalQuotes' => $totalQuotes,
            'submittedQuotes' => $submittedQuotes,
            'avgQuotesPerRfq' => round($avgQuotesPerRfq, 2),
            'avgQuoteValue' => round($avgQuoteValue, 2),
            'totalQuoteValue' => round($totalQuoteValue, 2),
            'acceptanceRate' => round($acceptanceRate, 2),
        ];
    }

    /**
     * Calculate Field Assessment KPIs for a given period
     */
    public function calculateFieldAssessmentKpis(?string $startDate = null, ?string $endDate = null): array
    {
        $query = \App\Models\FieldAssessment::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalAssessments = $query->count();
        $completedAssessments = (clone $query)->where('status', 'completed')->count();

        // Calculate average time to start (from assignment to started_at)
        $started = (clone $query)->whereNotNull('started_at')->get();
        $avgTimeToStart = $started->map(function ($assessment) {
            return $assessment->created_at->diffInHours($assessment->started_at);
        })->avg() ?? 0;

        // Calculate average time to complete (from started_at to completed_at)
        $completed = (clone $query)->where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->get();
        $avgTimeToComplete = $completed->map(function ($assessment) {
            return $assessment->started_at->diffInHours($assessment->completed_at);
        })->avg() ?? 0;

        $successRate = $totalAssessments > 0
            ? ($completedAssessments / $totalAssessments) * 100
            : 0;

        return [
            'totalAssessments' => $totalAssessments,
            'completedAssessments' => $completedAssessments,
            'avgTimeToStart' => round($avgTimeToStart, 2),
            'avgTimeToComplete' => round($avgTimeToComplete, 2),
            'successRate' => round($successRate, 2),
        ];
    }
}
