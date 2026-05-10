<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('assignedActivities')->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);

        AuditLog::record('user_created', $user, ['name' => $user->name, 'role' => $user->role]);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    public function show(User $user): View
    {
        $user->loadCount(['assignedActivities', 'activityUpdates']);
        $recentUpdates = $user->activityUpdates()->with('activity')->latest()->limit(10)->get();

        return view('users.show', compact('user', 'recentUpdates'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', false);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        AuditLog::record('user_updated', $user, ['name' => $user->name]);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        AuditLog::record('user_deleted', $user, ['name' => $user->name]);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
