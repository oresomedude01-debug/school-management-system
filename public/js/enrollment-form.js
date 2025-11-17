// Enrollment Form JavaScript
const API = {
    async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        };

        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }

        return data;
    },

    post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
};

// Step Management
let currentStep = 1;
let verifiedToken = null;

function showNotification(message, type = 'info') {
    const notification = document.getElementById('notification');
    notification.className = `mb-6 rounded-lg p-4 ${
        type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
        'bg-blue-50 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    notification.classList.remove('hidden');

    if (type === 'success' || type === 'error') {
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    }
}

function updateStepIndicator(step) {
    // Update step indicators
    for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById(`step${i}-indicator`);
        const label = document.getElementById(`step${i}-label`);
        const line = document.getElementById(`line${i}`);

        if (i < step) {
            // Completed step
            indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white font-semibold';
            indicator.innerHTML = '✓';
            label.className = 'text-green-600 font-medium';
            if (line) line.className = 'w-24 h-1 bg-green-500';
        } else if (i === step) {
            // Current step
            indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold';
            indicator.textContent = i;
            label.className = 'text-indigo-600 font-medium';
        } else {
            // Future step
            indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold';
            indicator.textContent = i;
            label.className = 'text-gray-500';
            if (line) line.className = 'w-24 h-1 bg-gray-300';
        }
    }

    // Show/hide step content
    document.getElementById('step1').classList.toggle('hidden', step !== 1);
    document.getElementById('step2').classList.toggle('hidden', step !== 2);
    document.getElementById('step3').classList.toggle('hidden', step !== 3);

    currentStep = step;
}

// Token Verification Form
document.getElementById('tokenForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const tokenCode = document.getElementById('token_code').value.toUpperCase();

    try {
        showNotification('Verifying token...', 'info');

        const response = await API.post('/api/enrollment/verify-token', {
            token_code: tokenCode
        });

        if (response.success) {
            verifiedToken = tokenCode;

            // Update step 2 with token info
            document.getElementById('verified_token').value = tokenCode;
            document.getElementById('academic_year').textContent = response.token.academic_year;
            document.getElementById('intended_class').textContent = response.token.intended_class;

            showNotification('Token verified successfully!', 'success');

            // Move to step 2
            setTimeout(() => {
                updateStepIndicator(2);
            }, 1000);
        }
    } catch (error) {
        showNotification(error.message || 'Failed to verify token. Please check and try again.', 'error');
    }
});

// Back Button
document.getElementById('backBtn').addEventListener('click', function() {
    updateStepIndicator(1);
});

// Enrollment Form Submission
document.getElementById('enrollmentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Validate password confirmation
    const password = document.querySelector('[name="password"]').value;
    const passwordConfirmation = document.querySelector('[name="password_confirmation"]').value;

    if (password !== passwordConfirmation) {
        showNotification('Passwords do not match!', 'error');
        return;
    }

    try {
        showNotification('Submitting enrollment...', 'info');

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        const response = await API.post('/api/enrollment/enroll', data);

        if (response.success) {
            // Show success message
            document.getElementById('admission_number_display').textContent = response.admission_number;
            updateStepIndicator(3);
        }
    } catch (error) {
        showNotification(error.message || 'Enrollment failed. Please try again.', 'error');
    }
});

// Make token code uppercase as user types
document.getElementById('token_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
