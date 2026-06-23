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

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id')->unique();
            $table->foreignId('appointment_id')->constrained('appointments', 'appointment_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->integer('claim_month');
            $table->integer('claim_year');
            $table->decimal('hours_worked', 5, 2);
            $table->decimal('amount_claimed', 10, 2);
            $table->decimal('amount_to_fees', 10, 2)->default(0);
            $table->decimal('amount_to_bank', 10, 2)->default(0);
            $table->foreignId('approved_by_id')->nullable()->constrained('staff_members', 'staff_id');
            $table->enum('status', ['submitted', 'supervisor_approved', 'coordinator_approved', 'paid'])->default('submitted');
            $table->boolean('is_late_claim')->default(false);
            $table->boolean('locked_after_supervisor_approval')->default(false);
            $table->decimal('tax_rate_applied', 5, 4)->default(0.0);
            $table->unique(['appointment_id', 'claim_month', 'claim_year']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
