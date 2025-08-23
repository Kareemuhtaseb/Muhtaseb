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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['generated', 'sent', 'pending'])->default('pending');
            $table->json('report_data')->nullable(); // Store detailed report data
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            // Ensure unique reports per owner per month/year
            $table->unique(['owner_id', 'month', 'year']);
            
            // Indexes for performance
            $table->index(['owner_id', 'status']);
            $table->index(['month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
