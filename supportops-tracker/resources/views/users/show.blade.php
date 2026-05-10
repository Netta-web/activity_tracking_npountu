@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('users.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-bold text-gray-900">Personnel Profile</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Profile Card --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-brand-500 to-purple-700 rounded-3xl flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 class="text-base font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>

                <div class="flex items-center justify-center gap-2 mt-3">
                    <span @class([
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase',
                        'bg-brand-100 text-brand-700' => $user->role === 'admin',
                        'bg-blue-100 text-blue-700' => $user->role === 'support',
                    ])>{{ $user->role }}</span>
                    <span @class([
                        'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-emerald-100 text-emerald-700' => $user->is_active,
                        'bg-gray-100 text-gray-500' => !$user->is_active,
                    ])>
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                @if($user->department)
                <p class="text-sm text-gray-500 mt-3">🏢 {{ $user->department }}</p>
                @endif

                <p class="text-xs text-gray-400 mt-2">Member since {{ $user->created_at->format('M Y') }}</p>

                <div class="grid grid-cols-3 gap-3 mt-5 pt-5 border-t border-gray-100">
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $user->assigned_activities_count }}</p>
                        <p class="text-[10px] text-gray-400 leading-tight">Assigned</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $user->activity_updates_count }}</p>
                        <p class="text-[10px] text-gray-400 leading-tight">Updates</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $recentUpdates->count() }}</p>
                        <p class="text-[10px] text-gray-400 leading-tight">Recent</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="flex-1 py-2 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-xl transition-colors text-center">
                        Edit
                    </a>
                    @if(auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="py-2 px-3 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                            Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Recent Activity Updates</h3>
                    <p class="text-xs text-gray-400">Last {{ $recentUpdates->count() }} updates by {{ $user->name }}</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentUpdates as $update)
                    <div class="flex items-start gap-4 px-6 py-4">
                        <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 @if($update->status === 'done') bg-emerald-500 @elseif($update->status === 'in_progress') bg-blue-500 @else bg-amber-400 @endif"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <a href="{{ route('activities.show', $update->activity_id) }}" class="text-sm font-semibold text-gray-900 hover:text-brand-600 transition-colors truncate">
                                    {{ $update->activity->title ?? 'Deleted Activity' }}
                                </a>
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase flex-shrink-0',
                                    'bg-emerald-100 text-emerald-700' => $update->status === 'done',
                                    'bg-blue-100 text-blue-700'       => $update->status === 'in_progress',
                                    'bg-amber-100 text-amber-700'     => $update->status === 'pending',
                                ])>{{ str_replace('_', ' ', $update->status) }}</span>
                            </div>
                            @if($update->remark)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $update->remark }}</p>
                            @endif
                            <p class="text-[10px] text-gray-400 mt-1">{{ $update->created_at->format('M j, Y · H:i') }} · {{ $update->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-400">No updates recorded yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
