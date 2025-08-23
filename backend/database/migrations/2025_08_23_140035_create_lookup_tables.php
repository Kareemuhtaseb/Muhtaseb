<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Property types lookup table
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Unit types lookup table
        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Payment methods lookup table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Maintenance categories lookup table
        Schema::create('maintenance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Document types lookup table
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category', ['property', 'tenant', 'financial', 'legal', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Notification types lookup table
        Schema::create('notification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add property_type_id to properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('property_type_id')->nullable()->constrained()->onDelete('set null')->after('location');
        });

        // Add unit_type_id to units table
        Schema::table('units', function (Blueprint $table) {
            $table->foreignId('unit_type_id')->nullable()->constrained()->onDelete('set null')->after('unit_number');
        });

        // Add indexes
        Schema::table('properties', function (Blueprint $table) {
            $table->index(['property_type_id', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->index(['unit_type_id', 'status']);
        });
    }

    public function down()
    {
        // Remove indexes
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['property_type_id', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['unit_type_id', 'status']);
        });

        // Remove foreign key columns
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropColumn(['property_type_id']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['unit_type_id']);
            $table->dropColumn(['unit_type_id']);
        });

        // Drop lookup tables
        Schema::dropIfExists('notification_types');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('maintenance_categories');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('unit_types');
        Schema::dropIfExists('property_types');
    }
};
