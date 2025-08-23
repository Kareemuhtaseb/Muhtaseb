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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('monthly_amount', 10, 2);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'annually'])->default('monthly');
            $table->enum('status', ['active', 'completed', 'cancelled', 'expired'])->default('active');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->text('renewal_terms')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'start_date']);
            $table->index(['end_date']);
            $table->index(['client_name']);
            $table->index(['property_id', 'unit_id']);
            $table->index(['category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
