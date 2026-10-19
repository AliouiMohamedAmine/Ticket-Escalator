<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    /**
     * Display the ticket list with optional status/priority filters.
     */
    public function index(Request $request): View
    {
        $filters = array_filter([
            'status'   => $request->query('status'),
            'priority' => $request->query('priority'),
        ]);

        $tickets = $this->ticketService->getAllFiltered($filters);

        return view('tickets.index', compact('tickets', 'filters'));
    }

    /**
     * Show the ticket creation form.
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Validate, persist, run urgency analysis, and redirect.
     * StoreTicketRequest handles all validation — controller stays thin.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->ticketService->createTicket($request->validated());

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Show a single ticket's detail page.
     */
    public function show(int $id): View
    {
        $ticket = $this->ticketService->findById($id);

        abort_if($ticket === null, 404, 'Ticket not found.');

        return view('tickets.show', compact('ticket'));
    }
}
