@extends('layouts.app')
@section('title', 'Activities')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Activity Management</h2>
            <p class="text-sm text-gray-400">Track and manage all operational activities</p>
        </div>
        @can('create', App\Models\Activity::class)
        <a href="{{ route('activities.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm shadow-brand-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            New Activity
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('activities.index') }}" x-data="{ expanded: false }">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activities..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-gray-700">
                    </div>
                </div>
                <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-gray-700 bg-white">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                </select>
                <select name="priority" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-gray-700 bg-white">
                    <option value="">All Priorities</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'category']))
                    <a href="{{ route('activities.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">
                        Clear
                    </a>
                    @endif
                </div>
            </div>
            <div class="mt-3" x-show="expanded" x-collapse>
                <select name="category" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-gray-700 bg-white">
                    <option value="">All Categories</option>
                    @foreach(App\Models\Activity::$categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" @click="expanded = !expanded" class="mt-2 text-xs text-brand-600 hover:text-brand-700 font-medium flex items-center gap-1">
                <span x-text="expanded ? 'Fewer filters' : 'More filters'">More filters</span>
                <svg class="w-3 h-3 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </form>
    </div>

    {{-- Activities Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing <span class="font-semibold text-gray-900">{{ $activities->firstItem() }}–{{ $activities->lastItem() }}</span>
                of <span class="font-semibold text-gray-900">{{ $activities->total() }}</span> activities
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Activity</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Category</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Priority</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Assigned To</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Due Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div>
                                <a href="{{ route('activities.show', $activity) }}" class="text-sm font-semibold text-gray-900 hover:text-brand-600 transition-colors group-hover:text-brand-600">
                                    {{ $activity->title }}
                                </a>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <x-avatar name="{{ $activity->creator->name ?? 'S' }}" size="xs" />
                                    <span class="text-xs text-gray-400">{{ $activity->creator->name ?? 'System' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-medium">
                                {{ $activity->category }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                                'bg-red-100 text-red-700'    => $activity->priority === 'critical',
                                'bg-orange-100 text-orange-700' => $activity->priority === 'high',
                                'bg-amber-100 text-amber-700'   => $activity->priority === 'medium',
                                'bg-green-100 text-green-700'   => $activity->priority === 'low',
                            ])>
                                @if($activity->priority === 'critical')
                                <span class="mr-1">⚡</span>
                                @endif
                                {{ $activity->priority }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span @class([
                                'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $activity->current_status === 'done',
                                'bg-blue-100 text-blue-700'       => $activity->current_status === 'in_progress',
                                'bg-amber-100 text-amber-700'     => $activity->current_status === 'pending',
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full @if($activity->current_status === 'done') bg-emerald-500 @elseif($activity->current_status === 'in_progress') bg-blue-500 @else bg-amber-400 @endif"></span>
                                {{ ucwords(str_replace('_', ' ', $activity->current_status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($activity->assignee)
                            <div class="flex items-center gap-2">
                                <x-avatar name="{{ $activity->assignee->name }}" size="md" />
                                <span class="text-sm text-gray-700">{{ $activity->assignee->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($activity->due_date)
                            <span class="{{ $activity->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }} text-sm flex items-center gap-1">
                                @if($activity->isOverdue())
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @endif
                                {{ $activity->due_date->format('M j, Y') }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('activities.show', $activity) }}"
                                    class="p-1.5 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @can('update', $activity)
                                <a href="{{ route('activities.edit', $activity) }}"
                                    class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endcan
                                @can('delete', $activity)
                                <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                                    x-data onsubmit="return confirm('Delete this activity? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No activities found</p>
                                <p class="text-xs text-gray-400">Try adjusting your filters or create a new activity</p>
                                @can('create', App\Models\Activity::class)
                                <a href="{{ route('activities.create') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-xl hover:bg-brand-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Create Activity
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
