@extends('layouts.app')

@section('content')

<div class="max-w-2xl">

    {{-- Back link --}}
    <a href="{{ route('tickets.index') }}"
       class="inline-flex items-center text-sm text-indigo-600 hover:underline mb-5">
        ← Back to all tickets
    </a>

    <div class="bg-white border border-gray-200 rounded shadow-sm p-6">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                <p class="text-xs text-gray-400 mt-1">
                    Ticket #{{ $ticket->id }} &middot; Created {{ $ticket->created_at->format('D, d M Y H:i') }}
                </p>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-6">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Description</h2>
            <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $ticket->description }}</p>
        </div>

        <hr class="border-gray-100 mb-6">

        {{-- Metadata grid --}}
        <div class="grid grid-cols-2 gap-4 text-sm">

            {{-- Priority --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Priority</h2>
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
            </div>

            {{-- Status --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</h2>
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
            </div>

            {{-- Urgency --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Auto-Detected Urgency</h2>
                @php
                    $urgencyClasses = [
                        'low'      => 'bg-gray-100 text-gray-600',
                        'medium'   => 'bg-yellow-100 text-yellow-700',
                        'high'     => 'bg-orange-100 text-orange-700',
                        'critical' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $urgencyClasses[$ticket->urgency] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($ticket->urgency) }}
                </span>
            </div>

            {{-- Urgency Source --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Urgency Source</h2>
                @if ($ticket->urgency_source === 'ai')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">
                        🤖 AI Analysis
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                        🔑 Keyword Fallback
                    </span>
                @endif
            </div>

        </div>

    </div>

</div>

@endsection
