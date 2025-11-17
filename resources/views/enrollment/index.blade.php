<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Enrollment - School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
        .animate-slideIn {
            animation: slideIn 0.5s ease-out;
        }
        .animate-pulse-slow {
            animation: pulse 2s ease-in-out infinite;
        }
        .step-indicator {
            transition: all 0.3s ease;
        }
        .step-indicator.active {
            background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%);
            color: white;
            transform: scale(1.1);
        }
        .step-indicator.completed {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12 animate-fadeIn">
            <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                Student Enrollment
            </h1>
            <p class="text-xl text-gray-600">Join our school community - Complete your enrollment in 4 easy steps</p>
        </div>

        <!-- Progress Steps -->
        <div class="max-w-4xl mx-auto mb-12">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="step-indicator active mx-auto w-12 h-12 rounded-full flex items-center justify-center font-bold shadow-lg mb-2" id="step-indicator-1">1</div>
                    <p class="text-sm font-medium text-gray-700">Token Validation</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-purple-600 transition-all duration-500" id="progress-1-2" style="width: 0%"></div>
                </div>
                <div class="flex-1 text-center">
                    <div class="step-indicator mx-auto w-12 h-12 rounded-full flex items-center justify-center font-bold bg-gray-200 text-gray-500 shadow-lg mb-2" id="step-indicator-2">2</div>
                    <p class="text-sm font-medium text-gray-700">Personal Info</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-purple-600 transition-all duration-500" id="progress-2-3" style="width: 0%"></div>
                </div>
                <div class="flex-1 text-center">
                    <div class="step-indicator mx-auto w-12 h-12 rounded-full flex items-center justify-center font-bold bg-gray-200 text-gray-500 shadow-lg mb-2" id="step-indicator-3">3</div>
                    <p class="text-sm font-medium text-gray-700">School & Health</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-purple-600 transition-all duration-500" id="progress-3-4" style="width: 0%"></div>
                </div>
                <div class="flex-1 text-center">
                    <div class="step-indicator mx-auto w-12 h-12 rounded-full flex items-center justify-center font-bold bg-gray-200 text-gray-500 shadow-lg mb-2" id="step-indicator-4">4</div>
                    <p class="text-sm font-medium text-gray-700">Guardian Info</p>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 animate-fadeIn">

                <!-- Step 1: Token Validation -->
                <div id="step-1" class="step-content">
                    <div class="text-center mb-8">
                        <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                            <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Enter Your Registration Token</h2>
                        <p class="text-gray-600">Please enter the 8-character token provided by the school</p>
                    </div>

                    <form id="tokenForm">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Token</label>
                            <input
                                type="text"
                                id="token_code"
                                name="token_code"
                                maxlength="8"
                                placeholder="XXXXXXXX"
                                class="w-full px-6 py-4 text-center text-2xl font-mono font-bold tracking-widest uppercase border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required
                            >
                            <p class="text-sm text-gray-500 mt-2">Token is case-insensitive and should be 8 characters long</p>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2"
                            >
                                Validate Token
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Personal Information -->
                <div id="step-2" class="step-content hidden">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Student Personal Information</h2>

                    <form id="personalForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Name</label>
                                <input type="text" name="middle_name" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" name="date_of_birth" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                                <select name="gender" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nationality *</label>
                                <input type="text" name="nationality" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                            <textarea name="address" rows="3" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Student Photo (Optional)</label>
                            <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <p class="text-sm text-gray-500 mt-1">Maximum file size: 2MB (JPEG, JPG, PNG)</p>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(1)" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                                Back
                            </button>
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                                Next Step
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Previous School & Health -->
                <div id="step-3" class="step-content hidden">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Previous School & Health Information</h2>

                    <form id="healthForm">
                        <h3 class="text-xl font-bold text-gray-700 mb-4">Previous School (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">School Name</label>
                                <input type="text" name="previous_school_name" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Previous Class</label>
                                <input type="text" name="previous_class" placeholder="e.g., Grade 5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">School Address</label>
                            <textarea name="previous_school_address" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Transfer</label>
                            <textarea name="transfer_reason" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                        </div>
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Previous School Results (Optional)</label>
                            <input type="file" name="previous_result" accept=".pdf,image/jpeg,image/jpg,image/png" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <p class="text-sm text-gray-500 mt-1">Maximum file size: 5MB (PDF, JPEG, JPG, PNG)</p>
                        </div>

                        <h3 class="text-xl font-bold text-gray-700 mb-4">Health Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Allergies</label>
                                <textarea name="allergies" rows="3" placeholder="List any allergies..." class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Medical Conditions</label>
                                <textarea name="medical_conditions" rows="3" placeholder="List any medical conditions..." class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-700 mb-4">Emergency Contact</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Emergency Contact Name *</label>
                                <input type="text" name="emergency_contact_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Emergency Contact Phone *</label>
                                <input type="tel" name="emergency_contact_phone" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                        </div>

                        <div class="mb-6 bg-blue-50 p-4 rounded-xl border-2 border-blue-200">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="emergency_medical_consent" value="1" required class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">
                                    <strong>Medical Consent *</strong><br>
                                    I give consent for emergency medical treatment if required and understand that every effort will be made to contact me first.
                                </span>
                            </label>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(2)" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                                Back
                            </button>
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                                Next Step
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 4: Guardian Information -->
                <div id="step-4" class="step-content hidden">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Parent/Guardian Information</h2>

                    <form id="guardianForm">
                        <h3 class="text-xl font-bold text-gray-700 mb-4">Primary Guardian *</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="parent_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="parent_phone" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="parent_email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <p class="text-sm text-gray-500 mt-1">This will be used to create your parent portal account</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                                <input type="password" name="parent_password" required minlength="8" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" name="parent_password_confirmation" required minlength="8" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Contact Method *</label>
                                <select name="preferred_contact_method" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Select Method</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Phone Call</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-700 mb-4">Secondary Guardian (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="guardian2_name" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Relationship</label>
                                <input type="text" name="guardian2_relationship" placeholder="e.g., Father, Mother, Uncle" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="guardian2_phone" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" name="guardian2_email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Occupation</label>
                                <input type="text" name="guardian2_occupation" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(3)" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                                Back
                            </button>
                            <button type="submit" class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Enrollment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Success Message -->
                <div id="success-message" class="hidden text-center">
                    <div class="animate-pulse-slow mb-6">
                        <div class="inline-block p-6 bg-green-100 rounded-full">
                            <svg class="w-24 h-24 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-4xl font-bold text-green-600 mb-4">Enrollment Successful!</h2>
                    <div id="success-details" class="bg-green-50 rounded-2xl p-6 mb-6 text-left">
                        <!-- Details will be populated by JavaScript -->
                    </div>
                    <p class="text-gray-600 mb-6">You will receive a confirmation email with further instructions.</p>
                    <a href="/login" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Go to Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let tokenId = null;
        let enrollmentData = {};

        // Token validation
        document.getElementById('tokenForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const tokenCode = document.getElementById('token_code').value.toUpperCase();

            try {
                const response = await fetch('/enrollment/validate-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ token_code: tokenCode })
                });

                const result = await response.json();

                if (result.success) {
                    tokenId = result.token.id;
                    enrollmentData.token_id = tokenId;
                    alert(result.message);
                    goToStep(2);
                } else {
                    alert(result.message || 'Invalid token. Please check and try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Personal information
        document.getElementById('personalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.forEach((value, key) => {
                enrollmentData[key] = value;
            });
            goToStep(3);
        });

        // Health information
        document.getElementById('healthForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.forEach((value, key) => {
                if (key === 'emergency_medical_consent') {
                    enrollmentData[key] = true;
                } else {
                    enrollmentData[key] = value;
                }
            });
            goToStep(4);
        });

        // Guardian information and final submission
        document.getElementById('guardianForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            formData.forEach((value, key) => {
                enrollmentData[key] = value;
            });

            // Create FormData for file upload
            const submitData = new FormData();
            Object.keys(enrollmentData).forEach(key => {
                submitData.append(key, enrollmentData[key]);
            });

            try {
                const response = await fetch('/enrollment/submit', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: submitData
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(result.data);
                } else {
                    alert('Error: ' + (result.message || 'Failed to submit enrollment'));
                    console.error(result.errors);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        function goToStep(step) {
            // Hide all steps
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-${i}`).classList.add('hidden');
                const indicator = document.getElementById(`step-indicator-${i}`);
                indicator.classList.remove('active');

                if (i < step) {
                    indicator.classList.add('completed');
                    if (i < 4) {
                        document.getElementById(`progress-${i}-${i+1}`).style.width = '100%';
                    }
                } else {
                    indicator.classList.remove('completed');
                    if (i < 4) {
                        document.getElementById(`progress-${i}-${i+1}`).style.width = '0%';
                    }
                }
            }

            // Show current step
            document.getElementById(`step-${step}`).classList.remove('hidden');
            document.getElementById(`step-indicator-${step}`).classList.add('active');
            currentStep = step;

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showSuccess(data) {
            // Hide all steps
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-${i}`).classList.add('hidden');
            }

            // Show success message
            const successDetails = document.getElementById('success-details');
            successDetails.innerHTML = `
                <h3 class="text-xl font-bold text-gray-800 mb-4">Enrollment Details:</h3>
                <div class="space-y-2">
                    <p class="text-gray-700"><strong>Student Name:</strong> ${data.student_name}</p>
                    <p class="text-gray-700"><strong>Admission Number:</strong> <span class="font-mono font-bold text-blue-600">${data.admission_number}</span></p>
                    <p class="text-gray-700"><strong>Parent Email:</strong> ${data.parent_email}</p>
                    <p class="text-gray-700"><strong>Enrollment Date:</strong> ${data.enrollment_date}</p>
                </div>
                <div class="mt-4 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>Important:</strong> Please save your admission number for future reference.
                        Your enrollment is currently in <strong>provisional</strong> status and will be reviewed by the administration.
                    </p>
                </div>
            `;

            document.getElementById('success-message').classList.remove('hidden');

            // Update all step indicators to completed
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-indicator-${i}`).classList.add('completed');
                document.getElementById(`step-indicator-${i}`).classList.remove('active');
                if (i < 4) {
                    document.getElementById(`progress-${i}-${i+1}`).style.width = '100%';
                }
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
