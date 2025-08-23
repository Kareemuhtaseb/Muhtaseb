<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenant_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('lease_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size')->nullable(); // in bytes
            $table->enum('document_type', ['id_proof', 'income_proof', 'rental_history', 'credit_report', 'employment_letter', 'bank_statement', 'other'])->default('other');
            $table->date('document_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'archived'])->default('active');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'document_type']);
            $table->index(['lease_id', 'document_type']);
            $table->index(['status', 'expiry_date']);
            $table->index(['uploaded_by', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenant_documents');
    }
};
