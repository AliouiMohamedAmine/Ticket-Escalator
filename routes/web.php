<?php

declare(strict_types=1);

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to the ticket list.
Route::redirect('/', '/tickets');

// Ticket resource routes (manual — no Route::resource to keep it explicit).
Route::get('/tickets',          [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create',   [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets',         [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{id}',     [TicketController::class, 'show'])->name('tickets.show');
