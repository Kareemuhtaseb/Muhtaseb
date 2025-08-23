<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('month_year'); // Format: YYYY-MM
            $table->decimal('total_income', 12, 2)->default(0);
            $table->decimal('total_expenses', 12, 2)->default(0);
            $table->decimal('net_income', 12, 2)->default(0);
            $table->integer('total_units')->default(0);
            $table->integer('occupied_units')->default(0);
            $table->decimal('occupancy_rate', 5, 2)->default(0); // Percentage
            $table->timestamps();
            
            $table->unique(['property_id', 'month_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_summaries');
    }
};
