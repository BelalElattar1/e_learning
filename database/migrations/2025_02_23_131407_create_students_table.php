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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 11)->unique();
            $table->string('father_phone', 11);
            $table->string('mother_phone', 11);
            $table->string('school_name');
            $table->string('father_job');
            $table->text('card_photo');
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('mayor_id')->constrained('mayors', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('academic_year_id')->constrained('academic_years', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
