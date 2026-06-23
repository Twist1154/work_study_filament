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

        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_detail_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete()->index();
            $table->string('account_type', 30);
            $table->string('account_number', 30);
            $table->string('bank_name', 100);
            $table->string('branch_name', 100)->nullable();
            $table->string('branch_code', 20)->nullable();
            $table->string('ownership_type', 20);
            $table->string('third_party_name', 200)->nullable();
            $table->string('third_party_relationship', 100)->nullable();
            $table->date('valid_from');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
