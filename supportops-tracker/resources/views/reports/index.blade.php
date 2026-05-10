@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Operations Reports</h2>
            <p class="text-sm text-gray-400">Historical activity logs and personnel action reports</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.export.csv', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter Report
        </h3>
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Personnel</label>
                    <select name="user_id" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        <option value="">All Personnel</option>
                        @foreach($personnel as $person)
                        <option value="{{ $person->id }}" {{ request('user_id') == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                    <select name="category" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Generate Report
                    </button>
                    @if(request()->hasAny(['start_date','end_date','user_id','category','status','priority']))
                    <a href="{{ route('reports.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors whitespace-nowrap">
                        Clear
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Summary Stats --}}
    @if(request()->hasAny(['start_date','end_date','user_id','category','status']))
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $summaryStats['total_updates'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Updates</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $summaryStats['done'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Done</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $summaryStats['in_progress'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">In Progress</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $summaryStats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pending</p>
        </div>
    </div>
    @endif

    {{-- Results Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Activity Update Log</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $updates->firstItem() }}–{{ $updates->lastItem() }} of {{ $updates->total() }} records
                    @if(request()->hasAny(['start_date','end_date','user_id','category','status']))
                    <span class="text-brand-600 font-medium">(filtered)</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Date & Time</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Activity</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Category</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Priority</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Updated By</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Remark</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($updates as $update)
                    <tr class="hover:bg-gray-50/40 transition-colors">
                        <td class="px-6 py-3.5">
                            <p class="text-xs font-semibold text-gray-900">{{ $update->created_at->format('M j, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $update->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($update->activity)
                            <a href="{{ route('activities.show', $update->activity) }}" class="text-sm font-semibold text-gray-900 hover:text-brand-600 transition-colors line-clamp-1 block max-w-xs">
                                {{ $update->activity->title }}
                            </a>
                            @else
                            <span class="text-xs text-gray-400 italic">Deleted activity</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-medium whitespace-nowrap">
                                {{ $update->activity->category ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($update->activity)
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase',
                                'bg-red-100 text-red-700'       => $update->activity->priority === 'critical',
                                'bg-orange-100 text-orange-700' => $update->activity->priority === 'high',
                                'bg-amber-100 text-amber-700'   => $update->activity->priority === 'medium',
                                'bg-green-100 text-green-700'   => $update->activity->priority === 'low',
                            ])>{{ $update->activity->priority }}</span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span @class([
                                'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $update->status === 'done',
                                'bg-blue-100 text-blue-700'       => $update->status === 'in_progress',
                                'bg-amber-100 text-amber-700'     => $update->status === 'pending',
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full @if($update->status === 'done') bg-emerald-500 @elseif($update->status === 'in_progress') bg-blue-500 @else bg-amber-400 @endif"></span>
                                {{ ucwords(str_replace('_', ' ', $update->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 text-[10px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($update->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700 whitespace-nowrap">{{ $update->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 max-w-xs">
                            @if($update->remark)
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $update->remark }}</p>
                            @else
                            <span class="text-xs text-gray-400 italic">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No records found</p>
                                <p class="text-xs text-gray-400">Try adjusting your filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($updates->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $updates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
