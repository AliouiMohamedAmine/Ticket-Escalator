@extends('layouts.app')

@section('content')

<div class="max-w-2xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Create New Ticket</h1>
        <p class="text-sm text-gray-500 mt-1">
            Urgency will be automatically determined from your description.
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded shadow-sm p-6">

        <form method="POST" action="{{ route('tickets.store') }}" novalidate>
            @csrf

            {{-- Title --}}
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    maxlength="255"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                           {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    placeholder="Brief summary of the issue"
                    aria-describedby="{{ $errors->has('title') ? 'title-error' : '' }}"
                >
                @error('title')
                    <p id="title-error" class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                           {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    placeholder="Describe the issue in detail. The urgency level will be auto-detected from this text."
                    aria-describedby="{{ $errors->has('description') ? 'description-error' : '' }}"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p id="description-error" class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Priority & Status (side by side) --}}
            <div class="grid grid-cols-2 gap-4 mb-6">

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="priority"
                        name="priority"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                               {{ $errors->has('priority') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        aria-describedby="{{ $errors->has('priority') ? 'priority-error' : '' }}"
                    >
                        <option value="">Select priority</option>
                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p id="priority-error" class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                               {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        aria-describedby="{{ $errors->has('status') ? 'status-error' : '' }}"
                    >
                        <option value="">Select status</option>
                        <option value="open"        {{ old('status') === 'open'        ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved"    {{ old('status') === 'resolved'    ? 'selected' : '' }}>Resolved</option>
                    </select>
                    @error('status')
                        <p id="status-error" class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-white text-sm px-5 py-2 rounded hover:bg-indigo-700 transition-colors font-medium">
                    Create Ticket
                </button>
                <a href="{{ route('tickets.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
