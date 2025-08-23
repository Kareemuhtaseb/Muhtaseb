<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size')->nullable(); // in bytes
            $table->enum('document_type', ['lease_agreement', 'property_deed', 'insurance', 'maintenance_record', 'inspection_report', 'financial_statement', 'other'])->default('other');
            $table->date('document_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'archived'])->default('active');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['property_id', 'document_type']);
            $table->index(['unit_id', 'document_type']);
            $table->index(['status', 'expiry_date']);
            $table->index(['uploaded_by', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_documents');
    }
};
