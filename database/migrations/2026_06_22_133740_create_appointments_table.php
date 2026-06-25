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

        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('job_category_id')->constrained('job_categories', 'job_category_id');
            $table->foreignId('department_id')->constrained('departments', 'department_id');
            $table->foreignId('campus_id')->constrained('campuses', 'campus_id');
            $table->foreignId('supervisor_id')->constrained('staff_members', 'staff_id');
            $table->foreignId('registration_id')->constrained('registrations', 'registration_id')->unique();
            $table->date('commencement_date');
            $table->date('termination_date')->nullable();
            $table->decimal('remuneration_rate_per_hour', 10, 2);
            $table->string('cost_centre', 20)->default('Y269');
            $table->enum('appointment_type', ['New Appointment', 'Renewal']);
            $table->enum('status', ['active', 'completed', 'terminated'])->default('active');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
