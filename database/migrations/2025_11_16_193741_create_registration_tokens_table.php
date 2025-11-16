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
        Schema::create('registration_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_code', 50)->unique();
            $table->enum('status', ['active', 'disabled', 'consumed', 'expired'])->default('active');
            $table->string('academic_year', 20)->nullable();
            $table->string('intended_class', 50)->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('status');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_tokens');
    }
};
