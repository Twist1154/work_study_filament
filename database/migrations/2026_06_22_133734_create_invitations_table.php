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

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invitation_id')->unique();
            $table->string('invitation_token', 64)->unique();
            $table->foreignId('job_category_id')->constrained('job_categories', 'job_category_id');
            $table->foreignId('department_id')->constrained('departments', 'department_id');
            $table->foreignId('campus_id')->constrained('campuses', 'campus_id');
            $table->foreignId('supervisor_id')->constrained('staff_members', 'staff_id');
            $table->string('first_names', 200)->nullable();
            $table->string('surname', 100)->nullable();
            $table->string('cost_centre', 20)->default('Y269');
            $table->dateTime('expires_at');
            $table->enum('status', ['sent', 'expired', 'accepted', 'cancelled'])->default('sent');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
