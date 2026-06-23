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
        Schema::disableForeignKeyConstraints();

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id');
            $table->foreignId('student_id')->nullable()->constrained('students', 'student_id')->index();
            $table->foreignId('registration_id')->nullable()->constrained('registrations', 'registration_id')->index();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments', 'appointment_id')->index();
            $table->string('document_type', 50);
            $table->string('file_path', 500);
            $table->date('permit_expiry_date')->nullable();
            $table->dateTime('uploaded_at')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
