@extends('layouts.app')
@section('title', 'Edit Activity')

@section('content')
<div class="max-w-3xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activities.show', $activity) }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-gray-900">Edit Activity</h2>
            <p class="text-sm text-gray-400">Update activity details and assignment</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('activities.update', $activity) }}" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Activity Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $activity->title) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('title') border-red-300 @enderror">
                @error('title')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none">{{ old('description', $activity->description) }}</textarea>
                @error('description')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $activity->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1.5">Priority <span class="text-red-500">*</span></label>
                    <select id="priority" name="priority" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        <option value="low" {{ old('priority', $activity->priority) === 'low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="medium" {{ old('priority', $activity->priority) === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="high" {{ old('priority', $activity->priority) === 'high' ? 'selected' : '' }}>🟠 High</option>
                        <option value="critical" {{ old('priority', $activity->priority) === 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                    </select>
                    @error('priority')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-1.5">Assign To</label>
                    <select id="assigned_to" name="assigned_to"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                        <option value="">Unassigned</option>
                        @foreach($personnel as $person)
                        <option value="{{ $person->id }}" {{ old('assigned_to', $activity->assigned_to) == $person->id ? 'selected' : '' }}>
                            {{ $person->name }}{{ $person->department ? ' — ' . $person->department : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Due Date</label>
                    <input type="date" id="due_date" name="due_date"
                        value="{{ old('due_date', $activity->due_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                    @error('due_date')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('activities.show', $activity) }}"
                    class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
