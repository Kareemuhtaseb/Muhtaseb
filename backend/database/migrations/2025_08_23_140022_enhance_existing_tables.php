<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add user relationships to existing tables
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('notes');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('name');
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('phone');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('notes');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('deposit');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('income', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('receipt_number');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('amount');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('notes');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('occupancy_rate');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('paid_date');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });

        // Add property relationships to existing tables
        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade')->after('owner_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade')->after('owner_id');
        });

        // Add unit relationships to existing tables
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade')->after('property_id');
        });

        // Add lease relationships to existing tables
        Schema::table('income', function (Blueprint $table) {
            $table->foreignId('lease_id')->nullable()->constrained()->onDelete('cascade')->after('tenant_id');
        });

        // Add indexes for better performance
        Schema::table('properties', function (Blueprint $table) {
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->index(['property_id', 'status']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->index(['unit_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('income', function (Blueprint $table) {
            $table->index(['property_id', 'date']);
            $table->index(['unit_id', 'date']);
            $table->index(['tenant_id', 'date']);
            $table->index(['category_id', 'date']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['property_id', 'date']);
            $table->index(['unit_id', 'date']);
            $table->index(['category_id', 'date']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->index(['owner_id', 'date']);
            $table->index(['property_id', 'date']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->index(['property_id', 'month_year']);
            $table->index(['created_by', 'created_at']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['owner_id', 'status']);
            $table->index(['property_id', 'status']);
            $table->index(['invoice_date', 'status']);
            $table->index(['created_by', 'created_at']);
        });
    }

    public function down()
    {
        // Remove indexes
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->dropIndex(['unit_id', 'status']);
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('income', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['unit_id', 'date']);
            $table->dropIndex(['tenant_id', 'date']);
            $table->dropIndex(['category_id', 'date']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['unit_id', 'date']);
            $table->dropIndex(['category_id', 'date']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'date']);
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'month_year']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'status']);
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['invoice_date', 'status']);
            $table->dropIndex(['created_by', 'created_at']);
        });

        // Remove foreign key columns
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by']);
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('income', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['property_id']);
            $table->dropColumn(['created_by', 'updated_by', 'property_id']);
        });

        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['property_id']);
            $table->dropColumn(['created_by', 'updated_by', 'property_id']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id']);
        });

        Schema::table('income', function (Blueprint $table) {
            $table->dropForeign(['lease_id']);
            $table->dropColumn(['lease_id']);
        });
    }
};
