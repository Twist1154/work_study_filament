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

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->string('staff_number', 50)->nullable();
            $table->string('full_name', 150);
            $table->string('role', 50);
            $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id')->onDelete('set null')->index();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_members');
    }
};
