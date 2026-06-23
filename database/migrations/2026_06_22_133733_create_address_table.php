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

        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete()->index();
            $table->integer('street_number')->nullable();
            $table->string('street_name', 150)->nullable();
            $table->string('suburb', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('post_code', 20)->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
