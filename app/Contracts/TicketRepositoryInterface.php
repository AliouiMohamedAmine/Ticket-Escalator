<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Ticket;
use Illuminate\Support\Collection;

interface TicketRepositoryInterface
{
    /**
     * Return all tickets, optionally filtered by status and/or priority.
     *
     * @param  array<string, string>  $filters  Accepted keys: 'status', 'priority'
     * @return Collection<int, Ticket>
     */
    public function all(array $filters = []): Collection;

    /**
     * Persist a new ticket and return the hydrated model.
     *
     * @param  array<string, string>  $data
     */
    public function create(array $data): Ticket;

    /**
     * Find a ticket by its primary key. Returns null when not found.
     */
    public function findById(int $id): ?Ticket;
}
