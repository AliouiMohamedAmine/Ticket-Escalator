@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Support Tickets</h1>
    <a href="{{ route('tickets.create') }}"
       class="bg-indigo-600 text-white text-sm px-4 py-2 rounded hover:bg-indigo-700 transition-colors">
        + New Ticket
    </a>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('tickets.index') }}"
      class="flex flex-wrap gap-3 mb-6 bg-white border border-gray-200 rounded p-4">

    <div>
        <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
        <select name="status" id="status"
                class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All Statuses</option>
            <option value="open"        {{ ($filters['status'] ?? '') === 'open'        ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved"    {{ ($filters['status'] ?? '') === 'resolved'    ? 'selected' : '' }}>Resolved</option>
        </select>
    </div>

    <div>
        <label for="priority" class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
        <select name="priority" id="priority"
                class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All Priorities</option>
            <option value="low"    {{ ($filters['priority'] ?? '') === 'low'    ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high"   {{ ($filters['priority'] ?? '') === 'high'   ? 'selected' : '' }}>High</option>
        </select>
    </div>

    <div class="flex items-end gap-2">
        <button type="submit"
                class="bg-indigo-600 text-white text-sm px-4 py-1.5 rounded hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if (!empty($filters))
            <a href="{{ route('tickets.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 underline py-1.5">
                Clear
            </a>
        @endif
    </div>
</form>

{{-- Ticket Table --}}
@if ($tickets->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg">No tickets found.</p>
        <p class="text-sm mt-1">Try adjusting your filters or
            <a href="{{ route('tickets.create') }}" class="text-indigo-600 underline">create a new ticket</a>.
        </p>
    </div>
@else
    <div class="overflow-x-auto rounded border border-gray-200 shadow-sm">
        <table class="w-full text-sm bg-white">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Priority</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Urgency</th>
                    <th class="px-4 py-3 text-left">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('tickets.show', $ticket->id) }}"
                               class="text-indigo-600 hover:underline">
                                {{ $ticket->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $priorityClasses = [
                                    'low'    => 'bg-gray-100 text-gray-600',
                                    'medium' => 'bg-yellow-100 text-yellow-700',
                                    'high'   => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $priorityClasses[$ticket->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'open'        => 'bg-blue-100 text-blue-700',
                                    'in_progress' => 'bg-orange-100 text-orange-700',
                                    'resolved'    => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $urgencyClasses = [
                                    'low'      => 'bg-gray-100 text-gray-600',
                                    'medium'   => 'bg-yellow-100 text-yellow-700',
                                    'high'     => 'bg-orange-100 text-orange-700',
                                    'critical' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $urgencyClasses[$ticket->urgency] ?? 'bg-gray-100 text-gray-600' }}"
                                  title="Source: {{ $ticket->urgency_source === 'ai' ? 'AI Analysis' : 'Keyword Fallback' }}">
                                {{ ucfirst($ticket->urgency) }}
                            </span>
                            <span class="text-gray-400 text-xs ml-1">
                                ({{ $ticket->urgency_source === 'ai' ? 'AI' : 'KW' }})
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $ticket->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
