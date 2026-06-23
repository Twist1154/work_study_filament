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
            $table->unsignedBigInteger('document_id')->unique();
            $table->foreignId('student_id')->nullable()->constrained('students', 'student_id');
            $table->foreignId('registration_id')->nullable()->constrained('registrations', 'registration_id');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments', 'appointment_id');
            $table->enum('document_type', ['ID Copy', 'Proof of Registration', 'CV', 'Highest Qualification', 'SARS Tax Certificate', 'Tutor Training Certificate', 'Work Permit', 'Study Permit', 'Tax Declaration', 'Other']);
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
