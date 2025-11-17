@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Registration Token Management
            </h1>
            <p class="text-gray-600 mt-2">Generate and manage student enrollment tokens</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8" id="tokenStats">
            <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Tokens</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1" id="stat-total">0</h3>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Active</p>
                        <h3 class="text-3xl font-bold text-green-600 mt-1" id="stat-active">0</h3>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Consumed</p>
                        <h3 class="text-3xl font-bold text-purple-600 mt-1" id="stat-consumed">0</h3>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Disabled</p>
                        <h3 class="text-3xl font-bold text-gray-600 mt-1" id="stat-disabled">0</h3>
                    </div>
                    <div class="bg-gray-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Expired</p>
                        <h3 class="text-3xl font-bold text-red-600 mt-1" id="stat-expired">0</h3>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-8">
            <button onclick="openGenerateModal('single')" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Generate Single Token
            </button>
            <button onclick="openGenerateModal('bulk')" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                </svg>
                Bulk Generate Tokens
            </button>
            <button onclick="exportTokens()" class="bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Token</label>
                    <input type="text" id="searchInput" placeholder="Enter token code..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="consumed">Consumed</option>
                        <option value="disabled">Disabled</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                    <input type="text" id="yearFilter" placeholder="e.g., 2024-2025" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div class="flex items-end">
                    <button onclick="filterTokens()" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Tokens Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-600 to-purple-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Token Code</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Academic Year</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Intended Class</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tokensTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-lg font-medium">Loading tokens...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Generate Token Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 transition-all duration-300" id="modalContent">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-2xl">
            <h2 class="text-2xl font-bold text-white" id="modalTitle">Generate Token</h2>
        </div>
        <form id="generateForm" class="p-6">
            <div id="quantityField" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity (Max 100)</label>
                <input type="number" id="quantity" name="quantity" min="1" max="100" value="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year *</label>
                <input type="text" id="academic_year" name="academic_year" placeholder="e.g., 2024-2025" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Intended Class</label>
                <input type="text" id="intended_class" name="intended_class" placeholder="e.g., Grade 1A" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                <textarea id="note" name="note" rows="3" placeholder="Optional note..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                    Generate
                </button>
                <button type="button" onclick="closeGenerateModal()" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentMode = 'single';
let currentFilters = {
    search: '',
    status: 'all',
    academic_year: ''
};

// Load tokens on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadTokens();
});

// Load statistics
async function loadStatistics() {
    try {
        const response = await fetch('/api/registration-tokens/statistics');
        const data = await response.json();

        document.getElementById('stat-total').textContent = data.total || 0;
        document.getElementById('stat-active').textContent = data.active || 0;
        document.getElementById('stat-consumed').textContent = data.consumed || 0;
        document.getElementById('stat-disabled').textContent = data.disabled || 0;
        document.getElementById('stat-expired').textContent = data.expired || 0;
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

// Load tokens
async function loadTokens() {
    try {
        const params = new URLSearchParams(currentFilters);
        const response = await fetch(`/api/registration-tokens?${params}`);
        const data = await response.json();

        if (data.success) {
            renderTokensTable(data.tokens.data);
        }
    } catch (error) {
        console.error('Error loading tokens:', error);
    }
}

// Render tokens table
function renderTokensTable(tokens) {
    const tbody = document.getElementById('tokensTableBody');

    if (!tokens || tokens.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-lg font-medium">No tokens found</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = tokens.map(token => {
        const statusColors = {
            active: 'bg-green-100 text-green-800',
            consumed: 'bg-purple-100 text-purple-800',
            disabled: 'bg-gray-100 text-gray-800',
            expired: 'bg-red-100 text-red-800'
        };

        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-mono font-bold text-blue-600">${token.token_code}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[token.status] || 'bg-gray-100 text-gray-800'}">
                        ${token.status.charAt(0).toUpperCase() + token.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${token.academic_year || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${token.intended_class || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${token.expiry_date ? new Date(token.expiry_date).toLocaleDateString() : '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(token.created_at).toLocaleDateString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    ${token.status !== 'consumed' ? `
                        <button onclick="toggleTokenStatus(${token.id}, '${token.status}')" class="text-blue-600 hover:text-blue-900 mr-3">
                            ${token.status === 'active' ? 'Disable' : 'Enable'}
                        </button>
                        <button onclick="deleteToken(${token.id})" class="text-red-600 hover:text-red-900">Delete</button>
                    ` : `
                        <span class="text-gray-400">Consumed</span>
                    `}
                </td>
            </tr>
        `;
    }).join('');
}

// Open generate modal
function openGenerateModal(mode) {
    currentMode = mode;
    document.getElementById('generateModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = mode === 'bulk' ? 'Bulk Generate Tokens' : 'Generate Single Token';
    document.getElementById('quantityField').classList.toggle('hidden', mode !== 'bulk');
    document.getElementById('generateForm').reset();

    // Animate modal
    setTimeout(() => {
        document.getElementById('modalContent').classList.remove('scale-95');
        document.getElementById('modalContent').classList.add('scale-100');
    }, 10);
}

// Close generate modal
function closeGenerateModal() {
    document.getElementById('modalContent').classList.remove('scale-100');
    document.getElementById('modalContent').classList.add('scale-95');
    setTimeout(() => {
        document.getElementById('generateModal').classList.add('hidden');
    }, 300);
}

// Handle form submission
document.getElementById('generateForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    const url = currentMode === 'bulk' ? '/api/registration-tokens/bulk' : '/api/registration-tokens';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            closeGenerateModal();
            loadStatistics();
            loadTokens();
        } else {
            alert('Error: ' + (result.message || 'Failed to generate token'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while generating the token');
    }
});

// Filter tokens
function filterTokens() {
    currentFilters.search = document.getElementById('searchInput').value;
    currentFilters.status = document.getElementById('statusFilter').value;
    currentFilters.academic_year = document.getElementById('yearFilter').value;
    loadTokens();
}

// Toggle token status
async function toggleTokenStatus(id, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'disabled' : 'active';

    if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'enable' : 'disable'} this token?`)) {
        return;
    }

    try {
        const response = await fetch(`/api/registration-tokens/${id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus })
        });

        const result = await response.json();

        if (result.success) {
            loadStatistics();
            loadTokens();
        } else {
            alert('Error: ' + (result.message || 'Failed to update token status'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while updating the token');
    }
}

// Delete token
async function deleteToken(id) {
    if (!confirm('Are you sure you want to delete this token? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch(`/api/registration-tokens/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            loadStatistics();
            loadTokens();
        } else {
            alert('Error: ' + (result.message || 'Failed to delete token'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting the token');
    }
}

// Export tokens
function exportTokens() {
    const params = new URLSearchParams(currentFilters);
    window.location.href = `/api/registration-tokens/export?${params}`;
}
</script>
@endsection
