<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    /**
     * Return tickets ordered newest-first, with optional status/priority filters.
     *
     * All filtering uses Eloquent's parameterized bindings — no raw SQL string
     * interpolation, satisfying OWASP A03 (Injection) requirements.
     *
     * @param  array<string, string>  $filters
     * @return Collection<int, Ticket>
     */
    public function all(array $filters = []): Collection
    {
        $query = Ticket::query()->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            // whereIn with a single value still uses a parameterized binding.
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->get();
    }

    /**
     * Persist a new ticket using Eloquent mass-assignment (respects $fillable).
     *
     * @param  array<string, string>  $data
     */
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /**
     * Find by primary key; returns null when the ticket does not exist.
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }
}
