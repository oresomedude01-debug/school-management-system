@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Student Management
            </h1>
            <p class="text-gray-600 mt-2">Add and manage students directly</p>
        </div>

        <!-- Add Student Form -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Student</h2>

            <form id="addStudentForm" enctype="multipart/form-data">
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
                    <textarea name="address" rows="2" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Admission Status *</label>
                        <select name="admission_status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="provisional">Provisional</option>
                            <option value="fully_admitted">Fully Admitted</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Student Photo (Optional)</label>
                        <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-700 mb-4 mt-8">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Name *</label>
                        <input type="text" name="emergency_contact_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone *</label>
                        <input type="tel" name="emergency_contact_phone" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="emergency_medical_consent" value="1" required class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Emergency Medical Consent *</span>
                    </label>
                </div>

                <h3 class="text-xl font-bold text-gray-700 mb-4 mt-8">Parent/Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Name *</label>
                        <input type="text" name="parent_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Email *</label>
                        <input type="email" name="parent_email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Phone *</label>
                        <input type="tel" name="parent_phone" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Password *</label>
                        <input type="password" name="parent_password" required minlength="8" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Contact Method *</label>
                        <select name="preferred_contact_method" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="email">Email</option>
                            <option value="phone">Phone Call</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Add Student
                    </button>
                </div>
            </form>
        </div>

        <!-- Students List -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">All Students</h2>
            <div id="studentsTable">
                <p class="text-center text-gray-500 py-8">Loading students...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Load students on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
});

// Submit form
document.getElementById('addStudentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/api/student-management', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('Student added successfully! Admission Number: ' + result.student.admission_number);
            e.target.reset();
            loadStudents();
        } else {
            alert('Error: ' + (result.message || 'Failed to add student'));
            console.error(result.errors);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while adding the student');
    }
});

// Load students
async function loadStudents() {
    try {
        const response = await fetch('/api/student-management');
        const data = await response.json();

        if (data.success) {
            renderStudentsTable(data.students.data);
        }
    } catch (error) {
        console.error('Error loading students:', error);
    }
}

// Render students table
function renderStudentsTable(students) {
    const container = document.getElementById('studentsTable');

    if (!students || students.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 py-8">No students found</p>';
        return;
    }

    container.innerHTML = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Admission No.</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Admission</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Enrolled</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${students.map(student => `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-bold text-blue-600">${student.admission_number}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${student.first_name} ${student.last_name}</div>
                                <div class="text-sm text-gray-500">${student.parent_email || ''}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.gender}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    student.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                }">
                                    ${student.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    student.admission_status === 'fully_admitted' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'
                                }">
                                    ${student.admission_status === 'fully_admitted' ? 'Fully Admitted' : 'Provisional'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${
                                student.enrollment_date ? new Date(student.enrollment_date).toLocaleDateString() : '-'
                            }</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}
</script>
@endsection
