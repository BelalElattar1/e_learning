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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->text('link')->nullable();
            $table->integer('time')->nullable();
            $table->integer('exam_mark')->nullable();
            $table->boolean('is_active')->comment('1 = Active && 0 = Not Active');
            $table->enum('type', ['video', 'pdf', 'exam']);
            $table->foreignId('category_id')->constrained('categories', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('teacher_id')->constrained('teachers', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
