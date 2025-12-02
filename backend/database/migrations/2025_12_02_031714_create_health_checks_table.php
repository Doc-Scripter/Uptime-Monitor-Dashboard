<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to monitors
            $table->foreignId('monitor_id')->constrained()->onDelete('cascade');
            
            // Check results
            $table->enum('status', ['up', 'down', 'warning'])->default('up');
            $table->integer('response_time')->nullable()->comment('Response time in milliseconds');
            $table->integer('http_code')->nullable();
            $table->text('error_message')->nullable();
            
            // Timing
            $table->timestamp('checked_at');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('monitor_id');
            $table->index('checked_at');
            $table->index(['monitor_id', 'checked_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_checks');
    }
};
