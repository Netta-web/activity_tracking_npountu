@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Total Activities</p>
        </div>

        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Pending</p>
        </div>

        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">In Progress</p>
        </div>

        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['done'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Completed</p>
        </div>

        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['critical'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Critical</p>
        </div>

        <div class="col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Overdue</p>
        </div>
    </div>

    {{-- Completion Progress --}}
    @if($stats['total'] > 0)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Overall Completion Rate</h3>
                <p class="text-xs text-gray-400">Based on all tracked activities</p>
            </div>
            <span class="text-2xl font-bold text-brand-600">{{ $stats['total'] > 0 ? round(($stats['done'] / $stats['total']) * 100) : 0 }}%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
            <div class="bg-gradient-to-r from-brand-500 to-brand-600 h-2.5 rounded-full transition-all duration-500"
                style="width: {{ $stats['total'] > 0 ? round(($stats['done'] / $stats['total']) * 100) : 0 }}%"></div>
        </div>
        <div class="flex gap-6 mt-3 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span>Done: {{ $stats['done'] }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 rounded-full"></span>In Progress: {{ $stats['in_progress'] }}</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-400 rounded-full"></span>Pending: {{ $stats['pending'] }}</span>
        </div>
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Recent Activity Updates --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Recent Activity Updates</h3>
                    <p class="text-xs text-gray-400">Latest team updates across all activities</p>
                </div>
                <a href="{{ route('handover.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">View all →</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentUpdates as $update)
                <div @class([
                    'group flex items-start gap-4 pl-5 pr-6 py-4 hover:bg-slate-50 transition-colors duration-150 border-l-[3px]',
                    'border-emerald-400' => $update->status === 'done',
                    'border-blue-400'    => $update->status === 'in_progress',
                    'border-amber-400'   => $update->status === 'pending',
                ])>
                    {{-- Avatar --}}
                    <x-avatar name="{{ $update->user->name ?? 'U' }}" size="xl" class="shadow-sm ring-2 ring-white" />

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        {{-- PRIMARY: activity title --}}
                        <p class="text-sm font-semibold text-gray-900 leading-snug truncate">
                            {{ $update->activity->title ?? 'Deleted Activity' }}
                        </p>
                        {{-- SECONDARY: who + status badge --}}
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="text-xs font-medium text-gray-500">by {{ $update->user->name ?? 'Unknown' }}</span>
                            <span @class([
                                'inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border',
                                'bg-emerald-50 text-emerald-700 border-emerald-200' => $update->status === 'done',
                                'bg-blue-50 text-blue-700 border-blue-200'          => $update->status === 'in_progress',
                                'bg-amber-50 text-amber-700 border-amber-200'       => $update->status === 'pending',
                            ])>{{ $update->status_label }}</span>
                        </div>
                        {{-- TERTIARY: remark --}}
                        @if($update->remark)
                        <p class="text-xs text-gray-400 mt-1.5 line-clamp-1 italic">{{ $update->remark }}</p>
                        @endif
                    </div>

                    {{-- Timestamp --}}
                    <span class="text-[11px] font-medium text-gray-400 group-hover:text-gray-600 transition-colors flex-shrink-0 pt-0.5 whitespace-nowrap">
                        {{ $update->created_at->diffForHumans() }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No activity updates yet</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pending Handovers --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Pending Handovers</h3>
                    <p class="text-xs text-gray-400">Requires attention</p>
                </div>
                <a href="{{ route('handover.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">Full view →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pendingHandovers as $activity)
                <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <a href="{{ route('activities.show', $activity) }}" class="text-sm font-semibold text-gray-900 hover:text-brand-600 transition-colors line-clamp-1">
                            {{ $activity->title }}
                        </a>
                        <span @class([
                            'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase flex-shrink-0',
                            'bg-red-100 text-red-700' => $activity->priority === 'critical',
                            'bg-orange-100 text-orange-700' => $activity->priority === 'high',
                            'bg-amber-100 text-amber-700' => $activity->priority === 'medium',
                            'bg-green-100 text-green-700' => $activity->priority === 'low',
                        ])>{{ $activity->priority }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        @if($activity->assignee)
                        <span class="flex items-center gap-1.5">
                            <x-avatar name="{{ $activity->assignee->name }}" size="sm" />
                            {{ $activity->assignee->name }}
                        </span>
                        @endif
                        @if($activity->due_date)
                        <span class="{{ $activity->isOverdue() ? 'text-red-500 font-semibold' : '' }}">
                            Due {{ $activity->due_date->format('M j') }}
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">All caught up! 🎉</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent Activities --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Recent Activities</h3>
                    <p class="text-xs text-gray-400">Latest activity across all operations</p>
                </div>
                <a href="{{ route('activities.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">All activities →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentActivities as $activity)
                <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition-colors">
                    <div @class([
                        'w-2 h-2 rounded-full flex-shrink-0',
                        'bg-red-500' => $activity->priority === 'critical',
                        'bg-orange-500' => $activity->priority === 'high',
                        'bg-amber-400' => $activity->priority === 'medium',
                        'bg-green-500' => $activity->priority === 'low',
                    ])></div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('activities.show', $activity) }}" class="text-sm font-medium text-gray-900 hover:text-brand-600 transition-colors truncate block">{{ $activity->title }}</a>
                        <p class="text-xs text-gray-400 truncate">{{ $activity->category }}</p>
                    </div>
                    <span @class([
                        'inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase',
                        'bg-emerald-100 text-emerald-700' => $activity->current_status === 'done',
                        'bg-blue-100 text-blue-700' => $activity->current_status === 'in_progress',
                        'bg-amber-100 text-amber-700' => $activity->current_status === 'pending',
                    ])>{{ str_replace('_', ' ', $activity->current_status) }}</span>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">No recent activities found</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Personnel Stats (Admin) or Category Chart (Staff) --}}
        @if(auth()->user()->isAdmin())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Personnel Load</h3>
                    <p class="text-xs text-gray-400">Activity distribution per staff</p>
                </div>
                <a href="{{ route('users.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">Manage →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($personnelStats as $person)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <x-avatar name="{{ $person->name }}" size="lg" />
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $person->name }}</p>
                                <p class="text-xs text-gray-400">{{ $person->department ?? 'No dept.' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">{{ $person->total_assigned }}</p>
                            <p class="text-xs text-gray-400">assigned</p>
                        </div>
                    </div>
                    @if($person->total_assigned > 0)
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-brand-500 to-brand-600 h-1.5 rounded-full"
                            style="width: {{ round(($person->done_count / $person->total_assigned) * 100) }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $person->done_count }} done · {{ $person->pending_count }} pending</p>
                    @endif
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No active support staff found</p>
                </div>
                @endforelse
            </div>
        </div>
        @else
        {{-- Category Chart for non-admin --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-1">Activity by Category</h3>
            <p class="text-xs text-gray-400 mb-5">Distribution across operation types</p>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($stats['total'] > 0)
    const ctx = document.getElementById('categoryChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Done'],
                datasets: [{
                    data: [{{ $stats['pending'] }}, {{ $stats['in_progress'] }}, {{ $stats['done'] }}],
                    backgroundColor: ['#fbbf24', '#60a5fa', '#34d399'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } }
                }
            }
        });
    }
@endif
</script>
@endpush
