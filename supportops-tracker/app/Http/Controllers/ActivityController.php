<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Activity::class);

        $user = auth()->user();

        $query = $user->isAdmin()
            ? Activity::with(['assignee', 'creator'])
            : Activity::with(['assignee', 'creator'])->where('assigned_to', $user->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $activities = $query->latest()->paginate(15)->withQueryString();
        $personnel  = User::where('role', 'support')->where('is_active', true)->get();

        return view('activities.index', compact('activities', 'personnel'));
    }

    public function create(): View
    {
        $this->authorize('create', Activity::class);
        $personnel  = User::where('is_active', true)->orderBy('name')->get();
        $categories = Activity::$categories;

        return view('activities.create', compact('personnel', 'categories'));
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $activity = Activity::create([
            ...$request->validated(),
            'created_by'     => auth()->id(),
            'current_status' => 'pending',
        ]);

        AuditLog::record('activity_created', $activity, $request->validated());

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity): View
    {
        $this->authorize('view', $activity);
        $activity->load(['creator', 'assignee', 'updates.user']);

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $this->authorize('update', $activity);
        $personnel  = User::where('is_active', true)->orderBy('name')->get();
        $categories = Activity::$categories;

        return view('activities.edit', compact('activity', 'personnel', 'categories'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        $old = $activity->only(['title', 'category', 'priority', 'assigned_to', 'current_status']);

        $activity->update($request->validated());

        AuditLog::record('activity_updated', $activity, [
            'before' => $old,
            'after'  => $request->validated(),
        ]);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->authorize('delete', $activity);

        AuditLog::record('activity_deleted', $activity, ['title' => $activity->title]);
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
