<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Seed six sample tickets covering all priority/status combinations
     * and demonstrating both urgency tiers (critical through low).
     */
    public function run(): void
    {
        $tickets = [
            [
                'title'          => 'Production DB outage — all services down',
                'description'    => 'Complete production database outage. All services are down and users cannot log in. Emergency response required immediately.',
                'priority'       => 'high',
                'status'         => 'open',
                'urgency'        => 'critical',
                'urgency_source' => 'fallback',
            ],
            [
                'title'          => 'Login page is slightly slow at peak hours',
                'description'    => 'Users are reporting the login page is a bit slow during peak hours around 9 AM. Not a blocker but worth investigating.',
                'priority'       => 'low',
                'status'         => 'open',
                'urgency'        => 'medium',
                'urgency_source' => 'fallback',
            ],
            [
                'title'          => 'Export button broken on reports page',
                'description'    => 'The CSV export button on the reports dashboard is broken. Clicking it triggers a 500 error. Users are blocked from exporting data.',
                'priority'       => 'medium',
                'status'         => 'in_progress',
                'urgency'        => 'high',
                'urgency_source' => 'fallback',
            ],
            [
                'title'          => 'Add dark mode to the dashboard',
                'description'    => 'Several users have requested a dark mode option for the dashboard. This is a cosmetic enhancement, no functional issue.',
                'priority'       => 'low',
                'status'         => 'resolved',
                'urgency'        => 'low',
                'urgency_source' => 'fallback',
            ],
            [
                'title'          => 'API crash on checkout — revenue impact',
                'description'    => 'The checkout API is crashing intermittently causing transaction failures. Revenue impact detected. Critical fix needed in production.',
                'priority'       => 'high',
                'status'         => 'open',
                'urgency'        => 'critical',
                'urgency_source' => 'fallback',
            ],
            [
                'title'          => 'Weekly summary report is delayed',
                'description'    => 'The automated weekly summary report was not sent this Monday. The scheduler job may have missed its window.',
                'priority'       => 'medium',
                'status'         => 'open',
                'urgency'        => 'low',
                'urgency_source' => 'fallback',
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }
    }
}
