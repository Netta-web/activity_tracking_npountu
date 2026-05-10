@extends('layouts.app')
@section('title', 'Daily Handover')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Daily Handover Board</h2>
            <p class="text-sm text-gray-400">Operations status for {{ now()->format('l, F j Y') }} — Shift: {{ now()->format('H:i') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-semibold text-emerald-700">Live Status</span>
            </div>
        </div>
    </div>

    {{-- Today's Updates Summary --}}
    @if($todayUpdates->count() > 0)
    <div class="bg-gradient-to-r from-brand-900 to-purple-900 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold">Today's Activity Feed</h3>
                <p class="text-sm text-brand-300">{{ $todayUpdates->count() }} update(s) logged today</p>
            </div>
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
        <div class="space-y-2 max-h-40 overflow-y-auto">
            @foreach($todayUpdates as $update)
            <div class="flex items-center gap-3 p-2.5 bg-white/[0.07] rounded-xl">
                <div class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($update->user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-white/90 truncate">
                        <span class="font-semibold">{{ $update->user->name ?? 'Unknown' }}</span>
                        → <span class="text-brand-300">{{ $update->activity->title ?? 'Deleted' }}</span>
                    </p>
                </div>
                <span @class([
                    'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase flex-shrink-0',
                    'bg-emerald-500/30 text-emerald-300' => $update->status === 'done',
                    'bg-blue-500/30 text-blue-300'       => $update->status === 'in_progress',
                    'bg-amber-500/30 text-amber-300'     => $update->status === 'pending',
                ])>{{ str_replace('_', ' ', $update->status) }}</span>
                <span class="text-[10px] text-white/40 flex-shrink-0">{{ $update->created_at->format('H:i') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Pending Activities (main) --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pending & In Progress</h3>
                            <p class="text-xs text-gray-400">{{ $pendingActivities->total() }} activities require attention</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($pendingActivities as $activity)
                    <div class="px-6 py-5 hover:bg-gray-50/40 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <span @class([
                                        'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide',
                                        'bg-red-100 text-red-700'       => $activity->priority === 'critical',
                                        'bg-orange-100 text-orange-700' => $activity->priority === 'high',
                                        'bg-amber-100 text-amber-700'   => $activity->priority === 'medium',
                                        'bg-green-100 text-green-700'   => $activity->priority === 'low',
                                    ])>
                                        @if($activity->priority === 'critical') 🔴 @endif{{ $activity->priority }}
                                    </span>
                                    <span @class([
                                        'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold',
                                        'bg-blue-100 text-blue-700'   => $activity->current_status === 'in_progress',
                                        'bg-amber-100 text-amber-700' => $activity->current_status === 'pending',
                                    ])>
                                        <span class="w-1.5 h-1.5 rounded-full @if($activity->current_status === 'in_progress') bg-blue-500 animate-pulse @else bg-amber-400 @endif"></span>
                                        {{ ucwords(str_replace('_', ' ', $activity->current_status)) }}
                                    </span>
                                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $activity->category }}</span>
                                </div>

                                <a href="{{ route('activities.show', $activity) }}" class="text-base font-bold text-gray-900 hover:text-brand-600 transition-colors block mb-1.5">
                                    {{ $activity->title }}
                                </a>

                                {{-- Latest Update --}}
                                @if($activity->updates->first())
                                @php $latestUpdate = $activity->updates->first(); @endphp
                                <div class="flex items-start gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100 mb-2">
                                    <div class="w-5 h-5 bg-brand-600 rounded-full flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0 mt-0.5">
                                        {{ strtoupper(substr($latestUpdate->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-700">{{ $latestUpdate->user->name ?? 'Unknown' }} <span class="font-normal text-gray-400">· {{ $latestUpdate->created_at->diffForHumans() }}</span></p>
                                        @if($latestUpdate->remark)
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $latestUpdate->remark }}</p>
                                        @else
                                        <p class="text-xs text-gray-400 italic mt-0.5">No remark</p>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <p class="text-xs text-gray-400 italic mb-2">No updates recorded — needs attention</p>
                                @endif

                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                    @if($activity->assignee)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $activity->assignee->name }}
                                    </span>
                                    @else
                                    <span class="text-orange-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Unassigned
                                    </span>
                                    @endif
                                    @if($activity->due_date)
                                    <span class="{{ $activity->isOverdue() ? 'text-red-500 font-semibold' : '' }} flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $activity->isOverdue() ? '⚠ ' : '' }}Due {{ $activity->due_date->format('M j') }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        {{ $activity->updates->count() }} update(s)
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('activities.show', $activity) }}"
                                class="flex-shrink-0 px-3 py-2 bg-brand-50 hover:bg-brand-100 text-brand-600 text-xs font-semibold rounded-xl transition-colors">
                                Update →
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-base font-bold text-gray-700 mb-1">All Clear! 🎉</p>
                        <p class="text-sm text-gray-400">No pending activities. Great work team!</p>
                    </div>
                    @endforelse
                </div>

                @if($pendingActivities->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $pendingActivities->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Recently Completed --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
                    <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recently Completed</h3>
                        <p class="text-xs text-gray-400">Last {{ $recentlyCompleted->count() }} items</p>
                    </div>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentlyCompleted as $activity)
                    <div class="px-5 py-3.5 hover:bg-gray-50/50 transition-colors">
                        <a href="{{ route('activities.show', $activity) }}" class="block text-sm font-semibold text-gray-900 hover:text-brand-600 transition-colors line-clamp-1 mb-1">
                            ✅ {{ $activity->title }}
                        </a>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            @if($activity->updates->first())
                            <span>by {{ $activity->updates->first()->user->name ?? 'Unknown' }}</span>
                            <span>·</span>
                            <span>{{ $activity->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm text-gray-400">No completed activities yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Handover Checklist --}}
            <div class="bg-gradient-to-br from-brand-50 to-purple-50 border border-brand-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-brand-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7l2 2 4-4"/></svg>
                    Handover Checklist
                </h4>
                <div class="space-y-2">
                    @php
                        $pendingCount = $pendingActivities->where('current_status', 'pending')->count();
                        $inProgressCount = $pendingActivities->where('current_status', 'in_progress')->count();
                        $criticalCount = $pendingActivities->where('priority', 'critical')->count();
                    @endphp
                    <div class="flex items-center justify-between p-2 bg-white/60 rounded-lg">
                        <span class="text-xs font-medium text-gray-700">Pending items</span>
                        <span class="text-xs font-bold {{ $pendingCount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $pendingCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/60 rounded-lg">
                        <span class="text-xs font-medium text-gray-700">In progress</span>
                        <span class="text-xs font-bold text-blue-600">{{ $inProgressCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/60 rounded-lg">
                        <span class="text-xs font-medium text-gray-700">Critical items</span>
                        <span class="text-xs font-bold {{ $criticalCount > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $criticalCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/60 rounded-lg">
                        <span class="text-xs font-medium text-gray-700">Updates today</span>
                        <span class="text-xs font-bold text-brand-600">{{ $todayUpdates->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/60 rounded-lg">
                        <span class="text-xs font-medium text-gray-700">Completed today</span>
                        <span class="text-xs font-bold text-emerald-600">{{ $recentlyCompleted->count() }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-brand-100">
                    <p class="text-[10px] text-brand-600 font-medium">Generated: {{ now()->format('H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
