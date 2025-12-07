<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Ticket;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_service_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger(column: 'attempt_number');
            $table->boolean('success');
            $table->unsignedInteger('http_status_code');
            $table->text('response_message')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamps();

            $table->index(['ticket_id', 'attempt_number']);
            $table->index('attempted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_service_attempts');
    }
};
