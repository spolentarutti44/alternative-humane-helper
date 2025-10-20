@extends('layouts.admin')

@section('title', 'Donation Management')
@section('page-title', 'Donations')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-4">
        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </select>
        <select id="typeFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Types</option>
            <option value="monetary">Monetary</option>
            <option value="supplies">Supplies</option>
            <option value="services">Services</option>
        </select>
        <input type="date" id="startDate" class="border border-gray-300 rounded-lg px-4 py-2">
        <input type="date" id="endDate" class="border border-gray-300 rounded-lg px-4 py-2">
    </div>
    <button onclick="openAddModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Record Donation
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Donations</p>
                <p class="text-3xl font-bold text-green-600" id="totalAmount">$0</p>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-green-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">This Month</p>
                <p class="text-3xl font-bold text-blue-600" id="monthAmount">$0</p>
            </div>
            <i class="fas fa-calendar text-4xl text-blue-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Donors</p>
                <p class="text-3xl font-bold text-purple-600" id="donorCount">0</p>
            </div>
            <i class="fas fa-users text-4xl text-purple-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Recurring Donors</p>
                <p class="text-3xl font-bold text-orange-600" id="recurringCount">0</p>
            </div>
            <i class="fas fa-sync text-4xl text-orange-200"></i>
        </div>
    </div>
</div>

<!-- Donations Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody id="donationsTableBody" class="bg-white divide-y divide-gray-200">
            <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    async function loadDonations() {
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        
        let url = '/api/donations';
        const params = [];
        if (status) params.push(`status=${status}`);
        if (type) params.push(`donation_type=${type}`);
        if (params.length) url += '?' + params.join('&');
        
        const response = await fetch(url);
        const data = await response.json();
        
        displayDonations(data.data);
        updateStats(data.data);
    }
    
    function displayDonations(donations) {
        const tbody = document.getElementById('donationsTableBody');
        tbody.innerHTML = donations.map(donation => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">${donation.donation_date}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${donation.donor ? donation.donor.first_name + ' ' + donation.donor.last_name : 'Anonymous'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">
                    $${parseFloat(donation.amount).toFixed(2)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">${donation.donation_type}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">${donation.payment_method.replace('_', ' ')}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(donation.status)}">
                        ${donation.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${donation.tax_receipt_sent 
                        ? '<i class="fas fa-check-circle text-green-600"></i>' 
                        : '<button onclick="sendReceipt(' + donation.id + ')" class="text-blue-600 hover:text-blue-900"><i class="fas fa-envelope"></i> Send</button>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="viewDonation(${donation.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editDonation(${donation.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    function getStatusColor(status) {
        const colors = {
            'completed': 'bg-green-100 text-green-800',
            'pending': 'bg-yellow-100 text-yellow-800',
            'failed': 'bg-red-100 text-red-800',
            'refunded': 'bg-gray-100 text-gray-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    function updateStats(donations) {
        const completed = donations.filter(d => d.status === 'completed');
        const totalAmount = completed.reduce((sum, d) => sum + parseFloat(d.amount), 0);
        
        document.getElementById('totalAmount').textContent = '$' + totalAmount.toFixed(2);
        document.getElementById('monthAmount').textContent = '$' + totalAmount.toFixed(2); // Simplified
    }
    
    async function sendReceipt(id) {
        const response = await fetch(`/api/donations/${id}/receipt`, { method: 'POST' });
        if (response.ok) {
            alert('Receipt sent successfully!');
            loadDonations();
        }
    }
    
    document.getElementById('statusFilter').addEventListener('change', loadDonations);
    document.getElementById('typeFilter').addEventListener('change', loadDonations);
    
    loadDonations();
</script>
@endsection

