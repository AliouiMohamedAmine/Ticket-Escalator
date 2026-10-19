<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->string('priority');          // low | medium | high
            $table->string('status')->default('open'); // open | in_progress | resolved
            $table->string('urgency')->default('low'); // low | medium | high | critical
            $table->string('urgency_source')->default('fallback'); // ai | fallback
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
