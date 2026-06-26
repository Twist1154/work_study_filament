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
        Schema::create('work_logs', function (Blueprint $table) {
            // Standard custom primary key matching your database design [1.1.2]
            $table->id('work_log_id');

            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained('appointments', 'appointment_id')->cascadeOnDelete();
            $table->dateTime('clock_in_at');
            $table->dateTime('clock_out_at')->nullable();

            // Decimals handle exact fractional hours (e.g., 7.50 hours)
            $table->decimal('hours_worked', 5, 2)->nullable();
            $table->integer('lunch_break_minutes')->default(30); // Default break deduction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
