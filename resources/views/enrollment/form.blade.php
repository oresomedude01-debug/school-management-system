<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Enrollment - Excellence Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="h-16 w-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-2">
                Student Enrollment
            </h1>
            <p class="text-gray-600">Join Excellence Academy - Where Learning Meets Excellence</p>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <div id="step1-indicator" class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold">
                            1
                        </div>
                        <div class="w-24 h-1 bg-gray-300" id="line1"></div>
                    </div>
                    <div class="flex items-center">
                        <div id="step2-indicator" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">
                            2
                        </div>
                        <div class="w-24 h-1 bg-gray-300" id="line2"></div>
                    </div>
                    <div class="flex items-center">
                        <div id="step3-indicator" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">
                            3
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-2 text-sm px-8">
                    <span class="text-indigo-600 font-medium" id="step1-label">Verify Token</span>
                    <span class="text-gray-500" id="step2-label">Your Information</span>
                    <span class="text-gray-500" id="step3-label">Confirm & Submit</span>
                </div>
            </div>

            <!-- Notification Area -->
            <div id="notification" class="hidden mb-6 rounded-lg p-4"></div>

            <!-- Step 1: Token Verification -->
            <div id="step1" class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Enter Your Registration Token</h2>
                <form id="tokenForm">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration Token</label>
                        <input type="text" id="token_code" name="token_code" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:outline-none text-lg tracking-widest uppercase"
                               placeholder="XXXXXXXX"
                               maxlength="8">
                        <p class="mt-2 text-sm text-gray-500">Enter the 8-character registration token provided by the school</p>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                        Verify Token
                    </button>
                </form>
            </div>

            <!-- Step 2: Enrollment Form (Hidden initially) -->
            <div id="step2" class="hidden bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Enrollment</h2>
                <form id="enrollmentForm">
                    <input type="hidden" id="verified_token" name="token_code">

                    <!-- Token Info Display -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-xl">
                        <p class="text-sm text-gray-600"><strong>Academic Year:</strong> <span id="academic_year"></span></p>
                        <p class="text-sm text-gray-600"><strong>Intended Class:</strong> <span id="intended_class"></span></p>
                    </div>

                    <!-- Student Personal Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                                <input type="date" name="date_of_birth" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Parent/Guardian Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Parent/Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name *</label>
                                <input type="text" name="parent_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship *</label>
                                <select name="relationship_to_student" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                                    <option value="">Select Relationship</option>
                                    <option value="father">Father</option>
                                    <option value="mother">Mother</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Phone *</label>
                                <input type="tel" name="parent_phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Email</label>
                                <input type="email" name="parent_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Address</label>
                                <textarea name="parent_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Emergency Contact</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Name *</label>
                                <input type="text" name="emergency_contact_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Phone *</label>
                                <input type="tel" name="emergency_contact_phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship *</label>
                                <input type="text" name="emergency_contact_relationship" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Medical Information (Optional)</h3>
                        <textarea name="medical_info" rows="3" placeholder="Any allergies, medical conditions, or special needs..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none"></textarea>
                    </div>

                    <!-- Previous School (Optional) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Previous School Information (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Previous School Name</label>
                                <input type="text" name="previous_school" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Previous Grade/Class</label>
                                <input type="text" name="previous_grade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Transfer</label>
                                <textarea name="transfer_reason" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" id="backBtn" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                            Back
                        </button>
                        <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                            Submit Enrollment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Success Message (Hidden initially) -->
            <div id="step3" class="hidden bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="mb-6">
                    <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Enrollment Successful!</h2>
                <p class="text-gray-600 mb-4">Your admission number is:</p>
                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-6" id="admission_number_display"></p>
                <p class="text-gray-600 mb-8">Please save this admission number for future reference. You can now login to your student portal using your email and password.</p>
                <a href="/login" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                    Go to Login
                </a>
            </div>
        </div>
    </div>

    <script src="/js/enrollment-form.js"></script>
</body>
</html>
