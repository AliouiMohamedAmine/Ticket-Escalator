<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\TicketRepositoryInterface;
use App\Repositories\EloquentTicketRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * Binds the repository interface to its Eloquent implementation so that
     * any class type-hinting TicketRepositoryInterface receives an
     * EloquentTicketRepository from the IoC container.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketRepositoryInterface::class,
            EloquentTicketRepository::class,
        );
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        //
    }
}
