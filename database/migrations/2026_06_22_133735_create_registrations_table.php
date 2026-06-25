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

        Schema::create('registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->foreignId('invitation_id')->constrained('invitations', 'invitation_id')->unique();
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->enum('status', ['pending_student', 'pending_verification', 'pending_hod_approval', 'pending_final', 'approved', 'rejected'])->default('pending_student');
            $table->boolean('conditions_accepted')->default(false);
            $table->foreignId('verifier_id')->nullable()->constrained('staff_members', 'staff_id');
            $table->foreignId('hod_approver_id')->nullable()->constrained('staff_members', 'staff_id');
            $table->foreignId('final_approver_id')->nullable()->constrained('staff_members', 'staff_id');
            $table->string('hod_signature_file', 500)->nullable();
            $table->date('hod_signature_date')->nullable();
            $table->string('hod_signature_place', 200)->nullable();
            $table->string('claims_sheet_pdf_path', 500)->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
