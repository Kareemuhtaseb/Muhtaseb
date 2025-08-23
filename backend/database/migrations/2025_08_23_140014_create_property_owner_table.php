<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_owner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained()->onDelete('cascade');
            $table->decimal('ownership_percentage', 5, 2); // Individual ownership percentage for this property
            $table->date('ownership_start_date')->nullable();
            $table->date('ownership_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['property_id', 'owner_id']);
            $table->index(['property_id', 'status']);
            $table->index(['owner_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_owner');
    }
};
