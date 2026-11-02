<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salon_settings', function (Blueprint $table) {
            $table->id();

            // 1. Business Profile
            $table->string('salon_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // 2. Operational / Appointment Rules
            $table->time('opening_time')->default('08:00');
            $table->time('closing_time')->default('18:00');
            $table->integer('max_services_per_appointment')->default(2);

            // 3. Holidays & Partial Closures
            // Use 'text' for SQLite compatibility; Laravel 'array' casting handles the rest.
            $table->text('holidays')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_settings');
    }
};
