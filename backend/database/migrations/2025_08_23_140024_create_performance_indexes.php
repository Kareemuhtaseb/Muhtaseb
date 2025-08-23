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
        // Properties table indexes
        Schema::table('properties', function (Blueprint $table) {
            $table->index(['property_type_id', 'created_at']);
            $table->index(['created_by', 'updated_at']);
            $table->fullText(['name', 'location']);
        });

        // Units table indexes
        Schema::table('units', function (Blueprint $table) {
            $table->index(['property_id', 'status']);
            $table->index(['unit_type_id', 'status']);
            $table->index(['status', 'monthly_rent_expected']);
            $table->index(['created_at', 'updated_at']);
        });

        // Tenants table indexes
        Schema::table('tenants', function (Blueprint $table) {
            $table->index(['email', 'status']);
            $table->index(['phone', 'status']);
            $table->index(['created_at', 'updated_at']);
        });

        // Leases table indexes
        Schema::table('leases', function (Blueprint $table) {
            $table->index(['unit_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['status', 'end_date']);
        });

        // Income table indexes
        Schema::table('income', function (Blueprint $table) {
            $table->index(['property_id', 'date']);
            $table->index(['unit_id', 'date']);
            $table->index(['category_id', 'date']);
            $table->index(['date', 'amount']);
        });

        // Expenses table indexes
        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['property_id', 'date']);
            $table->index(['unit_id', 'date']);
            $table->index(['category_id', 'date']);
            $table->index(['date', 'amount']);
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['tenant_id', 'payment_date']);
            $table->index(['unit_id', 'payment_date']);
            $table->index(['property_id', 'payment_date']);
            $table->index(['payment_date', 'amount']);
            $table->index(['status', 'payment_date']);
        });

        // Maintenance requests table indexes
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->index(['property_id', 'status']);
            $table->index(['unit_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['status', 'priority']);
            $table->index(['request_date', 'status']);
        });

        // Invoices table indexes
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['owner_id', 'invoice_date']);
            $table->index(['status', 'due_date']);
            $table->index(['invoice_date', 'amount']);
        });

        // Owner distributions table indexes
        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->index(['owner_id', 'date']);
            $table->index(['property_id', 'date']);
            $table->index(['date', 'amount']);
        });

        // Monthly summaries table indexes
        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->index(['property_id', 'month', 'year']);
            $table->index(['month', 'year']);
        });

        // Notifications table indexes
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'read_at']);
            $table->index(['type', 'created_at']);
            $table->index(['priority', 'created_at']);
        });

        // Audit logs table indexes
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['event', 'created_at']);
        });

        // Property owner pivot table indexes
        Schema::table('property_owner', function (Blueprint $table) {
            $table->index(['property_id', 'status']);
            $table->index(['owner_id', 'status']);
        });

        // User property access pivot table indexes
        Schema::table('user_property_access', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['property_id', 'status']);
            $table->index(['access_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        Schema::table('user_property_access', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['access_level', 'status']);
        });

        Schema::table('property_owner', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['owner_id', 'status']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['auditable_type', 'auditable_id']);
            $table->dropIndex(['event', 'created_at']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'read_at']);
            $table->dropIndex(['type', 'created_at']);
            $table->dropIndex(['priority', 'created_at']);
        });

        Schema::table('monthly_summaries', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'month', 'year']);
            $table->dropIndex(['month', 'year']);
        });

        Schema::table('owner_distributions', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'date']);
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['date', 'amount']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'invoice_date']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['invoice_date', 'amount']);
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['unit_id', 'status']);
            $table->dropIndex(['assigned_to', 'status']);
            $table->dropIndex(['status', 'priority']);
            $table->dropIndex(['request_date', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'payment_date']);
            $table->dropIndex(['unit_id', 'payment_date']);
            $table->dropIndex(['property_id', 'payment_date']);
            $table->dropIndex(['payment_date', 'amount']);
            $table->dropIndex(['status', 'payment_date']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['unit_id', 'date']);
            $table->dropIndex(['category_id', 'date']);
            $table->dropIndex(['date', 'amount']);
        });

        Schema::table('income', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'date']);
            $table->dropIndex(['unit_id', 'date']);
            $table->dropIndex(['category_id', 'date']);
            $table->dropIndex(['date', 'amount']);
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->dropIndex(['unit_id', 'status']);
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropIndex(['start_date', 'end_date']);
            $table->dropIndex(['status', 'end_date']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['email', 'status']);
            $table->dropIndex(['phone', 'status']);
            $table->dropIndex(['created_at', 'updated_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['property_id', 'status']);
            $table->dropIndex(['unit_type_id', 'status']);
            $table->dropIndex(['status', 'monthly_rent_expected']);
            $table->dropIndex(['created_at', 'updated_at']);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['property_type_id', 'created_at']);
            $table->dropIndex(['created_by', 'updated_at']);
            $table->dropFullText(['name', 'location']);
        });
    }
};
