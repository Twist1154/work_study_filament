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

        Schema::create('workstudy_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terms_id')->unique();
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete()->index();
            $table->foreignId('supervisor_id')->nullable()->constrained('staff_members', 'staff_id');
            $table->string('student_signature_file', 500)->nullable();
            $table->date('student_signed_date')->nullable();
            $table->string('student_signed_place', 200)->nullable();
            $table->string('supervisor_signature_file', 500)->nullable();
            $table->date('supervisor_signed_date')->nullable();
            $table->string('supervisor_signed_place', 200)->nullable();
            $table->boolean('terms_accepted')->default(false);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workstudy_terms');
    }
};
