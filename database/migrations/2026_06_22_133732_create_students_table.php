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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->string('student_number', 50)->nullable();
            $table->string('surname', 100);
            $table->string('first_names', 200);
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('id_passport_number', 50)->nullable();
            $table->string('sars_tax_number', 20)->nullable();
            $table->boolean('is_foreign_student')->default(false);
            $table->string('work_permit_number', 50)->nullable();
            $table->date('work_permit_expiry')->nullable();
            $table->boolean('fee_account_outstanding')->default(true);
            $table->boolean('nsfas_funded')->default(false);
            $table->boolean('full_bursary_holder')->default(false);
            $table->boolean('bursary_settled_before_sem2')->default(false);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
