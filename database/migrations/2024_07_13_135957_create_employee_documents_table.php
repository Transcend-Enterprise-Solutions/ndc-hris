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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'saln',
                'ipcr',
                'bir1902',
                'bir1905',
                'bir2316',
                'employment_cert',
                'service_record'
            ]);
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->integer('file_size')->comment('File size in bytes');
            $table->timestamps();

            // Unique constraint to prevent duplicate document types per user
            $table->unique(['user_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
