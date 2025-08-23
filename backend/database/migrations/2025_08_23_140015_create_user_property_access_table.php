<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_property_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->enum('access_level', ['read', 'write', 'admin'])->default('read');
            $table->date('access_start_date')->nullable();
            $table->date('access_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->foreignId('granted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'property_id']);
            $table->index(['user_id', 'status']);
            $table->index(['property_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_property_access');
    }
};
