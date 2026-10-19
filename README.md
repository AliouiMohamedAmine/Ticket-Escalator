# 🎫 Fast-Track Support & Ticket Escalator

A lightweight support-ticket micro-system with **automated urgency detection**.  
Built with Laravel 12 · Blade · SQLite — zero infrastructure, clone and run.

---

## 🐳 Quickstart with Docker (recommended)

**Prerequisites:** [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running.

```bash
git clone <repo-url>
cd ticket-escalator
docker compose up --build
```

Open **http://localhost:8000** — the app is live with 6 seeded tickets. That's it.

To enable AI-powered urgency detection, set your OpenAI key before building:

```bash
# In docker-compose.yml, set:
OPENAI_API_KEY: "sk-...your-key-here..."

# Then rebuild:
docker compose up --build
```

To stop the container:
```bash
docker compose down
```

---

## 💻 Manual Setup (PHP + Composer)

**Prerequisites:** PHP 8.2+, Composer, SQLite.

```bash
git clone <repo-url>
cd ticket-escalator
composer install
cp .env.example .env
php artisan key:generate
php -r "touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

Open **http://localhost:8000**.

---

## Architecture

```
HTTP Request
    │
    ▼
TicketController          (thin — HTTP in/out only)
    │
    ▼
TicketService             (orchestration & business rules)
    │           │
    ▼           ▼
UrgencyService     TicketRepositoryInterface  ← bound by AppServiceProvider
(AI + fallback)         │
                         ▼
                 EloquentTicketRepository
                         │
                         ▼
                    Ticket (Model)
                         │
                         ▼
                   SQLite database
```

### Layer Responsibilities

| Layer | Class | Responsibility |
|---|---|---|
| Controller | `TicketController` | Receive request, call service, return response. No logic. |
| Service | `TicketService` | Orchestrate ticket creation + urgency analysis. |
| Service | `UrgencyService` | Call OpenAI API or run keyword fallback. |
| Interface | `TicketRepositoryInterface` | Contract for data access. |
| Repository | `EloquentTicketRepository` | Eloquent queries. Parameterized bindings only. |
| Model | `Ticket` | Schema mapping + `$fillable`. No logic. |

---

## Prerequisites

- PHP 8.2+
- Composer
- SQLite (bundled with most PHP installs — check with `php -m | grep sqlite`)

---

## Setup (60 seconds)

```bash
# 1. Clone the repository
git clone <repo-url>
cd ticket-escalator

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create the SQLite database file
touch database/database.sqlite

# 5. Run migrations + seed sample data
php artisan migrate --seed

# 6. Start the development server
php artisan serve
```

Open **http://localhost:8000** — the ticket list loads with 6 sample tickets.

---

## Optional: OpenAI Urgency Analysis

By default the app uses the **keyword-based fallback** for urgency detection (no API key needed).

To enable AI-powered urgency detection:

```bash
# In your .env file, set:
OPENAI_API_KEY=sk-...your-key-here...
```

When set, ticket urgency is determined by GPT-4o-mini with an 8-second timeout.  
On any failure (timeout, invalid key, rate limit), the app **silently falls back** to keyword analysis — no crashes, no 500 errors.

---

## Urgency Fallback — Keyword Tiers

| Urgency | Trigger keywords |
|---|---|
| Critical | outage, down, breach, critical, data loss, emergency, production |
| High | urgent, broken, crash, failure, not working, blocked, error |
| Medium | slow, issue, degraded, intermittent, warning |
| Low | _(default — nothing matched)_ |

---

## Endpoints

| Method | URI | Description |
|---|---|---|
| GET | `/` | Redirects to `/tickets` |
| GET | `/tickets` | List all tickets (filter by `?status=` or `?priority=`) |
| GET | `/tickets/create` | New ticket form |
| POST | `/tickets` | Submit new ticket |
| GET | `/tickets/{id}` | Ticket detail view |

---

## Security Measures

| Threat | Mitigation |
|---|---|
| SQL Injection | Eloquent ORM — all queries use parameterized bindings |
| XSS | Blade `{{ }}` auto-escaping on all user output — `{!! !!}` never used on user data |
| CSRF | `@csrf` on every form; Laravel `VerifyCsrfToken` middleware active |
| Mass Assignment | `$fillable` explicitly defined on `Ticket` model |
| Sensitive key leak | `OPENAI_API_KEY` in `.env` only; `.env` excluded from git |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` via `SecurityHeaders` middleware |
| MIME Sniffing | `X-Content-Type-Options: nosniff` via `SecurityHeaders` middleware |
| Input validation | `StoreTicketRequest` Form Request with strict rules — runs before any service call |

---

## Project Structure

```
app/
├── Contracts/
│   └── TicketRepositoryInterface.php
├── Http/
│   ├── Controllers/TicketController.php
│   ├── Middleware/SecurityHeaders.php
│   └── Requests/StoreTicketRequest.php
├── Models/Ticket.php
├── Providers/AppServiceProvider.php
├── Repositories/EloquentTicketRepository.php
└── Services/
    ├── TicketService.php
    └── UrgencyService.php
database/
├── migrations/2024_01_01_000000_create_tickets_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── TicketSeeder.php
resources/views/
├── layouts/app.blade.php
└── tickets/
    ├── create.blade.php
    ├── index.blade.php
    └── show.blade.php
routes/web.php
```
