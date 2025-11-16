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
        Schema::table('students', function (Blueprint $table) {
            // Personal Information (date_of_birth and gender already exist)
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('nationality')->nullable()->after('gender');
            $table->text('address')->nullable()->after('nationality');
            $table->string('photo_path')->nullable()->after('address');

            // Admission Information (admission_number already exists but needs to be nullable)
            $table->date('enrollment_date')->nullable()->after('admission_number');
            $table->enum('admission_status', ['provisional', 'fully_admitted'])->default('provisional')->after('enrollment_date');

            // Previous School Information
            $table->string('previous_school_name')->nullable()->after('admission_status');
            $table->text('previous_school_address')->nullable()->after('previous_school_name');
            $table->string('previous_class')->nullable()->after('previous_school_address');
            $table->text('transfer_reason')->nullable()->after('previous_class');
            $table->string('previous_result_path')->nullable()->after('transfer_reason');

            // Health & Allergy Information
            $table->text('allergies')->nullable()->after('previous_result_path');
            $table->text('medical_conditions')->nullable()->after('allergies');
            $table->string('emergency_contact_name')->nullable()->after('medical_conditions');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->boolean('emergency_medical_consent')->default(false)->after('emergency_contact_phone');

            // Guardian 2 Information (Parent/Guardian 1 is the user)
            $table->string('guardian2_name')->nullable()->after('emergency_medical_consent');
            $table->string('guardian2_relationship')->nullable()->after('guardian2_name');
            $table->string('guardian2_phone')->nullable()->after('guardian2_relationship');
            $table->string('guardian2_email')->nullable()->after('guardian2_phone');
            $table->string('guardian2_occupation')->nullable()->after('guardian2_email');

            // Additional Fields
            $table->enum('preferred_contact_method', ['email', 'phone', 'sms'])->default('email')->after('guardian2_occupation');
            $table->foreignId('registration_token_id')->nullable()->constrained('registration_tokens')->onDelete('set null')->after('preferred_contact_method');

            // Indexes
            $table->index('enrollment_date');
            $table->index('admission_status');
        });

        // Modify admission_number to be nullable (in a separate statement)
        Schema::table('students', function (Blueprint $table) {
            $table->string('admission_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'middle_name', 'last_name',
                'nationality', 'address', 'photo_path', 'enrollment_date',
                'admission_status', 'previous_school_name', 'previous_school_address',
                'previous_class', 'transfer_reason', 'previous_result_path', 'allergies',
                'medical_conditions', 'emergency_contact_name', 'emergency_contact_phone',
                'emergency_medical_consent', 'guardian2_name', 'guardian2_relationship',
                'guardian2_phone', 'guardian2_email', 'guardian2_occupation',
                'preferred_contact_method', 'registration_token_id'
            ]);
        });

        // Restore admission_number to not nullable
        Schema::table('students', function (Blueprint $table) {
            $table->string('admission_number')->nullable(false)->change();
        });
    }
};
