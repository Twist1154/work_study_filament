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

        Schema::create('tax_declarations', function (Blueprint $table) {
            $table->id('declaration_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete()->index();
            $table->boolean('works_less_than_22hrs')->default(true);
            $table->boolean('no_other_employer')->default(true);
            $table->text('declaration_text')->nullable();
            $table->string('signed_place', 200)->nullable();
            $table->date('declaration_date')->useCurrent();
            $table->decimal('tax_rate_applied', 5, 4)->default(0.0);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_declarations');
    }
};
