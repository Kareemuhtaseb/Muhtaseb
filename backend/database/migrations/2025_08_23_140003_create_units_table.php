<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('unit_number');
            $table->string('shop_name')->nullable();
            $table->string('shop_number')->nullable();
            $table->string('company_name')->nullable();
            $table->decimal('monthly_rent_expected', 10, 2);
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['property_id', 'unit_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
