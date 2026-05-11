@extends('layouts.app')
@section('title', $activity->title)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('activities.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $activity->title }}</h2>
                <div class="flex items-center gap-1.5 text-xs text-gray-400">
                    <x-avatar name="{{ $activity->creator->name ?? 'S' }}" size="xs" />
                    <span>{{ $activity->creator->name ?? 'System' }} · {{ $activity->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $activity)
            <a href="{{ route('activities.edit', $activity) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endcan
            @can('delete', $activity)
            <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                onsubmit="return confirm('Permanently delete this activity?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Activity Details --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Main Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-wrap gap-2 mb-5">
                    <span @class([
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                        'bg-red-100 text-red-700'    => $activity->priority === 'critical',
                        'bg-orange-100 text-orange-700' => $activity->priority === 'high',
                        'bg-amber-100 text-amber-700'   => $activity->priority === 'medium',
                        'bg-green-100 text-green-700'   => $activity->priority === 'low',
                    ])>⚡ {{ ucfirst($activity->priority) }} Priority</span>

                    <span @class([
                        'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-emerald-100 text-emerald-700' => $activity->current_status === 'done',
                        'bg-blue-100 text-blue-700'       => $activity->current_status === 'in_progress',
                        'bg-amber-100 text-amber-700'     => $activity->current_status === 'pending',
                    ])>
                        <span class="w-1.5 h-1.5 rounded-full @if($activity->current_status === 'done') bg-emerald-500 @elseif($activity->current_status === 'in_progress') bg-blue-500 @else bg-amber-400 @endif animate-pulse"></span>
                        {{ ucwords(str_replace('_', ' ', $activity->current_status)) }}
                    </span>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $activity->category }}
                    </span>

                    @if($activity->isOverdue())
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        OVERDUE
                    </span>
                    @endif
                </div>

                @if($activity->description)
                <div class="prose prose-sm text-gray-700 max-w-none">
                    <p class="text-sm leading-relaxed">{{ $activity->description }}</p>
                </div>
                @else
                <p class="text-sm text-gray-400 italic">No description provided.</p>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 pt-5 border-t border-gray-100">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Assigned To</p>
                        @if($activity->assignee)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-brand-600 rounded-full flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($activity->assignee->name, 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900">{{ $activity->assignee->name }}</span>
                        </div>
                        @else
                        <span class="text-sm text-gray-400 italic">Unassigned</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Due Date</p>
                        <p class="text-sm font-medium {{ $activity->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $activity->due_date ? $activity->due_date->format('M j, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Last Updated</p>
                        <p class="text-sm font-medium text-gray-900">{{ $activity->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Add Update Form --}}
            @can('addUpdate', $activity)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-brand-100 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    Add Status Update
                </h3>
                <form method="POST" action="{{ route('activity-updates.store', $activity) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-1">
                            <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5">New Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                                <option value="pending" {{ $activity->current_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $activity->current_status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $activity->current_status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="remark" class="block text-xs font-semibold text-gray-600 mb-1.5">Remark / Notes</label>
                            <input type="text" id="remark" name="remark" value="{{ old('remark') }}"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                                placeholder="What did you do? What's the current situation?">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Submit Update
                        </button>
                    </div>
                </form>
            </div>
            @endcan

            {{-- Update History --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Update History</h3>
                    <p class="text-xs text-gray-400">{{ $activity->updates->count() }} update(s) recorded</p>
                </div>

                @if($activity->updates->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400">No updates recorded yet</p>
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($activity->updates->sortByDesc('created_at') as $update)
                    <div class="px-6 py-4 flex gap-4">
                        <x-avatar name="{{ $update->user->name ?? 'U' }}" size="xl" class="mt-0.5" />
                        <div class="flex-1">
                            <div class="flex items-center flex-wrap gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $update->user->name ?? 'Unknown User' }}</span>
                                <span class="text-xs text-gray-400">changed status to</span>
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide',
                                    'bg-emerald-100 text-emerald-700' => $update->status === 'done',
                                    'bg-blue-100 text-blue-700'       => $update->status === 'in_progress',
                                    'bg-amber-100 text-amber-700'     => $update->status === 'pending',
                                ])>{{ str_replace('_', ' ', $update->status) }}</span>
                            </div>
                            @if($update->remark)
                            <div class="mt-1.5 px-3 py-2 bg-gray-50 rounded-xl border-l-2 border-brand-300">
                                <p class="text-sm text-gray-600">{{ $update->remark }}</p>
                            </div>
                            @endif
                            <p class="text-[10px] text-gray-400 mt-1.5">{{ $update->created_at->format('M j, Y \a\t H:i') }} · {{ $update->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Activity Details</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <span @class([
                            'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold',
                            'bg-emerald-100 text-emerald-700' => $activity->current_status === 'done',
                            'bg-blue-100 text-blue-700'       => $activity->current_status === 'in_progress',
                            'bg-amber-100 text-amber-700'     => $activity->current_status === 'pending',
                        ])>
                            <span class="w-2 h-2 rounded-full @if($activity->current_status === 'done') bg-emerald-500 @elseif($activity->current_status === 'in_progress') bg-blue-500 animate-pulse @else bg-amber-400 @endif"></span>
                            {{ ucwords(str_replace('_', ' ', $activity->current_status)) }}
                        </span>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Priority</p>
                        <span @class([
                            'inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold uppercase',
                            'bg-red-100 text-red-700'    => $activity->priority === 'critical',
                            'bg-orange-100 text-orange-700' => $activity->priority === 'high',
                            'bg-amber-100 text-amber-700'   => $activity->priority === 'medium',
                            'bg-green-100 text-green-700'   => $activity->priority === 'low',
                        ])>{{ $activity->priority }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Category</p>
                        <p class="text-sm font-medium text-gray-900">{{ $activity->category }}</p>
                    </div>
                    @if($activity->due_date)
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Due Date</p>
                        <p class="text-sm font-medium {{ $activity->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $activity->due_date->format('l, F j, Y') }}
                            @if($activity->isOverdue())
                            <span class="block text-xs text-red-500 mt-0.5">⚠ Overdue by {{ $activity->due_date->diffForHumans() }}</span>
                            @else
                            <span class="block text-xs text-gray-400 mt-0.5">{{ $activity->due_date->diffForHumans() }}</span>
                            @endif
                        </p>
                    </div>
                    @endif
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Created By</p>
                        <div class="flex items-center gap-2">
                            <x-avatar name="{{ $activity->creator->name ?? 'S' }}" size="md" />
                            <p class="text-sm font-medium text-gray-900">{{ $activity->creator->name ?? 'System' }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Assigned To</p>
                        @if($activity->assignee)
                        <div class="flex items-center gap-2">
                            <x-avatar name="{{ $activity->assignee->name }}" size="md" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $activity->assignee->name }}</p>
                                @if($activity->assignee->department)
                                <p class="text-xs text-gray-400">{{ $activity->assignee->department }}</p>
                                @endif
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-400 italic">Unassigned</p>
                        @endif
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-1">Total Updates</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activity->updates->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
