<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_applied', 10, 2);
            $table->date('application_date');
            $table->text('notes')->nullable();
            $table->foreignId('applied_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['invoice_id', 'payment_id']);
            $table->index(['invoice_id', 'application_date']);
            $table->index(['payment_id', 'application_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_payments');
    }
};
