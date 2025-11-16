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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Grade 1-A", "Grade 10-B"
            $table->string('grade_level'); // e.g., "1", "2", "10"
            $table->string('section'); // e.g., "A", "B", "C"
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null'); // Class teacher
            $table->string('room_number')->nullable();
            $table->integer('capacity')->default(30);
            $table->string('academic_year'); // e.g., "2024-2025"
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
