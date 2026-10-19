#  Ticket Escalator

A lightweight support-ticket system with automated urgency detection built as a technical assessment.

## Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Views:** Blade templates + Tailwind CSS (CDN)
- **Database:** SQLite
- **AI Module:** OpenAI GPT-4o-mini with keyword-based fallback

## Architecture

Strict 4-layer separation:

```
Controller → Service → Repository → Model
```

- **Controller** — handles HTTP in/out only, no business logic
- **Service** — all business logic and orchestration
- **Repository** — data access via `TicketRepositoryInterface` (bound in `AppServiceProvider`)
- **Model** — Eloquent schema mapping only

The urgency detection runs automatically on ticket creation. It calls the OpenAI API first and silently falls back to keyword scoring if the API is unavailable or the key is not set.

## Run with Docker

```bash
git clone https://github.com/AliouiMohamedAmine/Ticket-Escalator.git
cd Ticket-Escalator
docker compose up --build
```

Open **http://localhost:8000**

## Run Manually

**Requirements:** PHP 8.2+, Composer, SQLite

```bash
git clone https://github.com/AliouiMohamedAmine/Ticket-Escalator.git
cd Ticket-Escalator
composer install
cp .env.example .env
php artisan key:generate
php -r "touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

Open **http://localhost:8000**

## OpenAI (optional)

Leave `OPENAI_API_KEY` blank in `.env` to use the keyword fallback. Set it to enable AI-powered urgency detection.
