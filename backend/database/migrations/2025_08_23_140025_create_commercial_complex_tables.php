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
        // Commercial Complex table
        Schema::create('commercial_complexes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });

        // Shops/Units table
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_complex_id')->constrained()->onDelete('cascade');
            $table->string('shop_number'); // e.g., "1+2", "3", "4", "6+7+8"
            $table->string('shop_name'); // e.g., "Chocolography", "Sukaina", "Original Burger"
            $table->decimal('area', 10, 2)->nullable(); // in square meters
            $table->string('location_description')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->timestamps();
            
            $table->unique(['commercial_complex_id', 'shop_number']);
        });

        // Companies table (instead of tenants)
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_number')->nullable(); // Commercial registration
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('address')->nullable();
            $table->string('business_type')->nullable(); // e.g., "Restaurant", "Retail", "Office"
            $table->timestamps();
        });

        // Contracts table (main table linking companies to shops)
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('yearly_rent', 12, 2);
            $table->decimal('yearly_rent_with_tax', 12, 2);
            $table->decimal('yearly_services', 10, 2)->default(0);
            $table->decimal('monthly_rent', 10, 2); // Calculated field
            $table->decimal('monthly_rent_with_tax', 10, 2); // Calculated field
            $table->decimal('monthly_services', 10, 2); // Calculated field
            $table->enum('status', ['active', 'expired', 'terminated', 'pending'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['shop_id', 'status']);
            $table->index(['company_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });

        // Monthly Income table (for tracking actual income)
        Schema::create('monthly_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('expected_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['contract_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });

        // Income Transactions table (for detailed income tracking)
        Schema::create('income_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->enum('type', ['rent', 'services', 'penalty', 'other']);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'card'])->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['contract_id', 'transaction_date']);
            $table->index(['transaction_date', 'type']);
        });

        // Expenses table (for complex expenses)
        Schema::create('complex_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_complex_id')->constrained()->onDelete('cascade');
            $table->string('category'); // e.g., "Maintenance", "Utilities", "Insurance", "Management"
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('vendor')->nullable();
            $table->string('invoice_number')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'card'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['commercial_complex_id', 'expense_date']);
            $table->index(['category', 'expense_date']);
        });

        // Commercial Complex Monthly Summaries table (for reporting)
        Schema::create('commercial_complex_monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_complex_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('total_expected_income', 12, 2);
            $table->decimal('total_actual_income', 12, 2);
            $table->decimal('total_expenses', 12, 2);
            $table->decimal('net_income', 12, 2);
            $table->integer('active_contracts_count');
            $table->integer('paid_contracts_count');
            $table->integer('overdue_contracts_count');
            $table->timestamps();
            
            $table->unique(['commercial_complex_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercial_complex_monthly_summaries');
        Schema::dropIfExists('complex_expenses');
        Schema::dropIfExists('income_transactions');
        Schema::dropIfExists('monthly_incomes');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('commercial_complexes');
    }
};
