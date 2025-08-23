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
        // Add property and unit relationships to income table
        Schema::table('income', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->index(['property_id', 'unit_id']);
        });

        // Add property and unit relationships to expenses table
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->index(['property_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['property_id', 'unit_id']);
            $table->dropColumn(['property_id', 'unit_id']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['property_id', 'unit_id']);
            $table->dropColumn(['property_id', 'unit_id']);
        });
    }
};
