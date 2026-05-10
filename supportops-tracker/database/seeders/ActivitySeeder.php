<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin    = User::where('role', 'admin')->first();
        $support  = User::where('role', 'support')->get();

        $activities = [
            [
                'title'          => 'Production Server Health Monitoring',
                'description'    => 'Monitor all production servers (APP01-APP05) for CPU usage, memory consumption, and disk I/O. Alert threshold: CPU >85%, Memory >90%.',
                'category'       => 'Server Monitoring',
                'priority'       => 'critical',
                'assigned_to'    => $support->where('name', 'Alice Mensah')->first()?->id,
                'current_status' => 'in_progress',
                'due_date'       => now()->addDays(1)->toDateString(),
            ],
            [
                'title'          => 'SMS Gateway Throughput Check',
                'description'    => 'Validate SMS delivery rates and gateway connectivity. Expected throughput: >95% delivery success rate.',
                'category'       => 'SMS Monitoring',
                'priority'       => 'high',
                'assigned_to'    => $support->where('name', 'Eva Boateng')->first()?->id,
                'current_status' => 'pending',
                'due_date'       => now()->toDateString(),
            ],
            [
                'title'          => 'Database Replication Lag Validation',
                'description'    => 'Check MySQL replication lag across all slave nodes. Acceptable lag: <30 seconds. Document any anomalies.',
                'category'       => 'Database Validation',
                'priority'       => 'high',
                'assigned_to'    => $support->where('name', 'Bob Asante')->first()?->id,
                'current_status' => 'done',
                'due_date'       => now()->subDay()->toDateString(),
            ],
            [
                'title'          => 'Payment API Health Check',
                'description'    => 'Verify payment gateway API response times and error rates. Test endpoints: /charge, /refund, /verify.',
                'category'       => 'API Health Check',
                'priority'       => 'critical',
                'assigned_to'    => $support->where('name', 'David Amoah')->first()?->id,
                'current_status' => 'in_progress',
                'due_date'       => now()->addHours(4)->toDateString(),
            ],
            [
                'title'          => 'Network Latency Review — Branch Offices',
                'description'    => 'Measure network latency between HQ and all branch offices. Acceptable: <50ms local, <150ms international.',
                'category'       => 'Network Monitoring',
                'priority'       => 'medium',
                'assigned_to'    => $support->where('name', 'Carol Owusu')->first()?->id,
                'current_status' => 'pending',
                'due_date'       => now()->addDays(2)->toDateString(),
            ],
            [
                'title'          => 'Security Firewall Rule Audit',
                'description'    => 'Review and verify all firewall rules for compliance. Remove any deprecated or unauthorized rules.',
                'category'       => 'Security Audit',
                'priority'       => 'high',
                'assigned_to'    => $support->where('name', 'Frank Acheampong')->first()?->id,
                'current_status' => 'pending',
                'due_date'       => now()->addDays(3)->toDateString(),
            ],
            [
                'title'          => 'Automated Backup Verification',
                'description'    => 'Confirm all nightly database backups completed successfully. Test restore procedure on staging environment.',
                'category'       => 'Backup Verification',
                'priority'       => 'medium',
                'assigned_to'    => $support->where('name', 'Bob Asante')->first()?->id,
                'current_status' => 'done',
                'due_date'       => now()->subDays(1)->toDateString(),
            ],
            [
                'title'          => 'Incident Response: Login Service Degradation',
                'description'    => 'Investigate reports of slow login response times. Users reporting >10 second delays during peak hours. P1 incident.',
                'category'       => 'Incident Response',
                'priority'       => 'critical',
                'assigned_to'    => $support->where('name', 'Alice Mensah')->first()?->id,
                'current_status' => 'in_progress',
                'due_date'       => now()->toDateString(),
            ],
            [
                'title'          => 'Monthly Performance Review Report',
                'description'    => 'Compile monthly performance metrics for all monitored systems. Include uptime SLAs, incident count, and resolution times.',
                'category'       => 'Performance Review',
                'priority'       => 'low',
                'assigned_to'    => $support->where('name', 'David Amoah')->first()?->id,
                'current_status' => 'pending',
                'due_date'       => now()->addDays(7)->toDateString(),
            ],
            [
                'title'          => 'Application Support Ticket Resolution',
                'description'    => 'Clear backlog of pending Level 2 support tickets. Current backlog: 14 tickets assigned. Target: 80% closure rate.',
                'category'       => 'Application Support',
                'priority'       => 'medium',
                'assigned_to'    => $support->first()?->id,
                'current_status' => 'in_progress',
                'due_date'       => now()->addDays(1)->toDateString(),
            ],
        ];

        foreach ($activities as $data) {
            Activity::create([
                ...$data,
                'created_by' => $admin->id,
            ]);
        }

        $this->command->info('✅ ' . count($activities) . ' activities seeded');
    }
}
