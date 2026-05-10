<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Base query scoped to the authenticated user's visibility
        $activitiesQuery = $user->isAdmin()
            ? Activity::query()
            : Activity::where('assigned_to', $user->id);

        // ── Stats ────────────────────────────────────────────────────────────
        $stats = [
            'total'       => (clone $activitiesQuery)->count(),
            'pending'     => (clone $activitiesQuery)->where('current_status', 'pending')->count(),
            'in_progress' => (clone $activitiesQuery)->where('current_status', 'in_progress')->count(),
            'done'        => (clone $activitiesQuery)->where('current_status', 'done')->count(),
            'critical'    => (clone $activitiesQuery)->where('priority', 'critical')->count(),
            'overdue'     => (clone $activitiesQuery)
                ->where('current_status', '!=', 'done')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
        ];

        // ── Recent activity updates (timeline feed) ───────────────────────────
        $recentUpdates = ActivityUpdate::with(['activity', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        // ── Recent activities (replaces the fragile today-only filter) ────────
        // Shows the 8 most recently created/updated activities visible to the
        // current user. This works regardless of when the data was seeded.
        $recentActivities = (clone $activitiesQuery)
            ->with(['assignee', 'creator'])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        // ── Pending handovers (sorted by priority, then due date) ─────────────
        $pendingHandovers = (clone $activitiesQuery)
            ->with(['assignee', 'updates' => fn ($q) => $q->latest()->limit(1)])
            ->where('current_status', '!=', 'done')
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // ── Category distribution (for chart) ────────────────────────────────
        $categoryStats = Activity::selectRaw('category, COUNT(*) as count, current_status')
            ->groupBy('category', 'current_status')
            ->get()
            ->groupBy('category');

        // ── Personnel stats (admin only) ──────────────────────────────────────
        // Always fetched as a Collection so the view can use ->count() safely.
        $personnelStats = collect();
        if ($user->isAdmin()) {
            $personnelStats = User::where('role', 'support')
                ->where('is_active', true)
                ->withCount([
                    'assignedActivities as total_assigned',
                    'assignedActivities as pending_count' => fn ($q) => $q->where('current_status', 'pending'),
                    'assignedActivities as done_count'    => fn ($q) => $q->where('current_status', 'done'),
                ])
                ->orderBy('name')
                ->get();
        }

        return view('dashboard', compact(
            'stats',
            'recentUpdates',
            'recentActivities',
            'pendingHandovers',
            'categoryStats',
            'personnelStats',
        ));
    }
}
