<?php

namespace App\Jobs;

use App\Events\SlaReminderDue;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckRfqDeadlines implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all active RFQs (draft, open)
        $activeRfqs = Request::whereIn('status', ['draft', 'open'])
            ->where('deadline', '>', now())
            ->get();

        foreach ($activeRfqs as $rfq) {
            $daysRemaining = Carbon::now()->diffInDays($rfq->deadline, false);

            // Determine priority based on days remaining
            $priority = $this->determinePriority($daysRemaining);

            // Only send reminders for specific thresholds
            if ($this->shouldSendReminder($daysRemaining)) {
                event(new SlaReminderDue($rfq, $daysRemaining, $priority));
            }
        }
    }

    /**
     * Determine the priority based on days remaining.
     *
     * @param int $daysRemaining
     * @return string
     */
    private function determinePriority(int $daysRemaining): string
    {
        if ($daysRemaining <= 1) {
            return 'high';
        } elseif ($daysRemaining <= 3) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Determine if a reminder should be sent based on days remaining.
     *
     * @param int $daysRemaining
     * @return bool
     */
    private function shouldSendReminder(int $daysRemaining): bool
    {
        // Send reminders at specific thresholds: 7 days, 3 days, 1 day
        return in_array($daysRemaining, [7, 3, 1]);
    }
}
