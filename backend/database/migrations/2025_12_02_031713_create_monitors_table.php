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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('name');
            $table->string('url');
            $table->enum('type', ['website', 'api'])->default('website');
            
            // Monitoring configuration
            $table->integer('interval')->default(5)->comment('Check interval in minutes');
            $table->boolean('is_active')->default(true);
            
            // Current status
            $table->enum('status', ['up', 'down', 'warning'])->default('up');
            $table->decimal('uptime_percentage', 5, 2)->nullable()->comment('7-day uptime percentage');
            $table->integer('current_latency')->nullable()->comment('Latest response time in ms');
            
            // Metadata
            $table->json('tags')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('is_active');
            $table->index('last_checked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
