<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use Illuminate\View\View;

class HandoverController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $pendingActivities = ($user->isAdmin()
            ? Activity::query()
            : Activity::where('assigned_to', $user->id))
            ->with(['assignee', 'updates' => fn($q) => $q->with('user')->latest()->limit(3)])
            ->where('current_status', '!=', 'done')
            ->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->orderBy('due_date')
            ->paginate(20);

        $recentlyCompleted = ($user->isAdmin()
            ? Activity::query()
            : Activity::where('assigned_to', $user->id))
            ->with(['assignee', 'updates' => fn($q) => $q->with('user')->latest()->limit(1)])
            ->where('current_status', 'done')
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $todayUpdates = ActivityUpdate::with(['activity', 'user'])
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('handover.index', compact(
            'pendingActivities',
            'recentlyCompleted',
            'todayUpdates'
        ));
    }
}
