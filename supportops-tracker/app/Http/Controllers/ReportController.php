<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityUpdate::with(['activity.assignee', 'user'])
            ->join('activities', 'activity_updates.activity_id', '=', 'activities.id')
            ->select('activity_updates.*');

        if ($request->filled('start_date')) {
            $query->whereDate('activity_updates.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('activity_updates.created_at', '<=', $request->end_date);
        }

        if ($request->filled('user_id')) {
            $query->where('activity_updates.user_id', $request->user_id);
        }

        if ($request->filled('category')) {
            $query->where('activities.category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('activity_updates.status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('activities.priority', $request->priority);
        }

        $updates   = $query->latest('activity_updates.created_at')->paginate(25)->withQueryString();
        $personnel = User::where('is_active', true)->orderBy('name')->get();
        $categories = Activity::$categories;

        $summaryStats = [
            'total_updates' => $query->count(),
            'done'          => (clone $query)->where('activity_updates.status', 'done')->count(),
            'in_progress'   => (clone $query)->where('activity_updates.status', 'in_progress')->count(),
            'pending'       => (clone $query)->where('activity_updates.status', 'pending')->count(),
        ];

        return view('reports.index', compact('updates', 'personnel', 'categories', 'summaryStats'));
    }

    public function exportCsv(Request $request): Response
    {
        $query = ActivityUpdate::with(['activity', 'user'])
            ->join('activities', 'activity_updates.activity_id', '=', 'activities.id')
            ->select('activity_updates.*');

        $this->applyFilters($query, $request);

        $updates = $query->latest('activity_updates.created_at')->get();

        $csv = "Date/Time,Activity Title,Category,Priority,Status,Updated By,Remark\n";
        foreach ($updates as $update) {
            $csv .= implode(',', [
                '"' . $update->created_at->format('Y-m-d H:i:s') . '"',
                '"' . str_replace('"', '""', $update->activity->title ?? '') . '"',
                '"' . str_replace('"', '""', $update->activity->category ?? '') . '"',
                '"' . ucfirst($update->activity->priority ?? '') . '"',
                '"' . ucwords(str_replace('_', ' ', $update->status)) . '"',
                '"' . str_replace('"', '""', $update->user->name ?? '') . '"',
                '"' . str_replace('"', '""', $update->remark ?? '') . '"',
            ]) . "\n";
        }

        $filename = 'supportops-report-' . now()->format('Y-m-d') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('start_date')) {
            $query->whereDate('activity_updates.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('activity_updates.created_at', '<=', $request->end_date);
        }
        if ($request->filled('user_id')) {
            $query->where('activity_updates.user_id', $request->user_id);
        }
        if ($request->filled('category')) {
            $query->where('activities.category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('activity_updates.status', $request->status);
        }
    }
}
