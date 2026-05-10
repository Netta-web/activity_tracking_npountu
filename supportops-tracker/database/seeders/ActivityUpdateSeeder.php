<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $activities = Activity::all();
        $users      = User::all();

        $remarks = [
            'pending'     => [
                'Ticket received and logged. Initial assessment in progress.',
                'Scheduled for review during next shift.',
                'Awaiting access credentials from client.',
                'Dependencies not yet available. Blocked.',
            ],
            'in_progress' => [
                'Investigation started. Initial diagnostics running.',
                'Root cause identified. Working on resolution.',
                'Monitoring system response after applying initial fix.',
                'Escalated to senior team. Awaiting feedback.',
                'Running automated tests to confirm fix effectiveness.',
                'Patch deployed to staging environment. Testing in progress.',
            ],
            'done' => [
                'Resolved successfully. No further action required.',
                'Issue fixed and verified in production. Monitoring for 30 minutes.',
                'Completed within SLA. Full report attached.',
                'All checks passed. System operating within normal parameters.',
            ],
        ];

        $count = 0;
        foreach ($activities as $activity) {
            $numUpdates = rand(1, 4);
            $statuses   = $this->buildStatusProgression($activity->current_status, $numUpdates);
            $updater    = $activity->assigned_to
                ? User::find($activity->assigned_to)
                : $users->random();

            foreach ($statuses as $i => $status) {
                $statusRemarks = $remarks[$status];
                ActivityUpdate::create([
                    'activity_id' => $activity->id,
                    'user_id'     => $updater->id,
                    'status'      => $status,
                    'remark'      => $statusRemarks[array_rand($statusRemarks)],
                    'created_at'  => now()->subHours(($numUpdates - $i) * rand(2, 8)),
                    'updated_at'  => now()->subHours(($numUpdates - $i) * rand(2, 8)),
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} activity updates seeded");
    }

    private function buildStatusProgression(string $finalStatus, int $steps): array
    {
        $flow = ['pending', 'in_progress', 'done'];
        $finalIdx = array_search($finalStatus, $flow);

        if ($steps === 1) {
            return [$finalStatus];
        }

        $result = [];
        $startIdx = max(0, $finalIdx - $steps + 1);

        for ($i = $startIdx; $i <= $finalIdx; $i++) {
            $result[] = $flow[$i];
        }

        if (count($result) < $steps) {
            array_unshift($result, 'pending');
        }

        return array_slice($result, -$steps);
    }
}
