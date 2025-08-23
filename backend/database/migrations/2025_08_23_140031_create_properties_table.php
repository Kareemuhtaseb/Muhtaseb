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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->enum('property_type', ['residential', 'commercial', 'industrial', 'mixed'])->nullable();
            $table->enum('status', ['active', 'inactive', 'sold', 'under_construction'])->default('active');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->decimal('total_area', 10, 2)->nullable();
            $table->integer('total_units')->default(0);
            $table->integer('occupied_units')->default(0);
            $table->integer('year_built')->nullable();
            $table->decimal('monthly_rent_income', 15, 2)->default(0);
            $table->decimal('monthly_expenses', 15, 2)->default(0);
            $table->decimal('property_tax_rate', 5, 2)->default(0);
            $table->decimal('insurance_rate', 5, 2)->default(0);
            $table->decimal('management_fee_rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'property_type']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
