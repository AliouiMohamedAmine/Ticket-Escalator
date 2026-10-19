<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketService
{
    public function __construct(
        private readonly TicketRepositoryInterface $repository,
        private readonly UrgencyService $urgencyService,
    ) {}

    /**
     * Return all tickets, optionally filtered by status and/or priority.
     *
     * @param  array<string, string>  $filters
     * @return Collection<int, Ticket>
     */
    public function getAllFiltered(array $filters = []): Collection
    {
        return $this->repository->all($filters);
    }

    /**
     * Analyse urgency, merge the result, persist, and return the new ticket.
     *
     * @param  array<string, string>  $validated  Already-validated form data.
     */
    public function createTicket(array $validated): Ticket
    {
        $urgencyData = $this->urgencyService->analyse($validated['description']);

        return $this->repository->create(array_merge($validated, $urgencyData));
    }

    /**
     * Find a single ticket by ID; returns null if not found.
     */
    public function findById(int $id): ?Ticket
    {
        return $this->repository->findById($id);
    }
}
