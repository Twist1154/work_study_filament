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

        Schema::create('qualifications', function (Blueprint $table) {
            $table->id('qualification_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete()->index();
            $table->string('qualification_name', 200);
            $table->integer('year_obtained')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};
