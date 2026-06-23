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

        Schema::create('authentication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authentication_id');
            $table->string('email_address', 255)->unique();
            $table->string('password_hash', 255);
            $table->boolean('is_locked')->default(false);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication');
    }
};
