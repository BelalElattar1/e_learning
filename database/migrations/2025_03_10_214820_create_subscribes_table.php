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
        Schema::create('subscribes', function (Blueprint $table) {
            $table->id();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->string('pay_photo')->unique();
            $table->enum('status', ['pending', 'active', 'rejected', 'expired'])->default('pending');
            $table->string('reason_for_rejection')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribes');
    }
};
