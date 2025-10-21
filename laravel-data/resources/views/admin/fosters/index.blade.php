@extends('layouts.admin')

@section('title', 'Foster Management')
@section('page-title', 'Foster Families')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-4">
        <input type="text" id="searchInput" placeholder="Search fosters..." 
               class="border border-gray-300 rounded-lg px-4 py-2 w-64">
        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="pending">Pending</option>
        </select>
    </div>
    <button onclick="openAddModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Add New Foster
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Active Fosters</p>
                <p class="text-3xl font-bold text-green-600" id="activeCount">-</p>
            </div>
            <i class="fas fa-user-check text-4xl text-green-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Furry Friends Fostered</p>
                <p class="text-3xl font-bold text-blue-600" id="animalCount">-</p>
            </div>
            <i class="fas fa-paw text-4xl text-blue-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Available Capacity</p>
                <p class="text-3xl font-bold text-purple-600" id="capacityCount">-</p>
            </div>
            <i class="fas fa-home text-4xl text-purple-200"></i>
        </div>
    </div>
</div>

<!-- Fosters Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody id="fostersTableBody" class="bg-white divide-y divide-gray-200">
            <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    async function loadFosters() {
        const status = document.getElementById('statusFilter').value;
        let url = '/api/fosters';
        if (status) url += `?status=${status}`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        displayFosters(data.data);
        updateStats(data.data);
    }
    
    function displayFosters(fosters) {
        const tbody = document.getElementById('fostersTableBody');
        tbody.innerHTML = fosters.map(foster => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap font-medium">
                    ${foster.first_name} ${foster.last_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${foster.email}</td>
                <td class="px-6 py-4 whitespace-nowrap">${foster.phone}</td>
                <td class="px-6 py-4 whitespace-nowrap">${foster.capacity}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${foster.active_assignments ? foster.active_assignments.length : 0}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(foster.status)}">
                        ${foster.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="viewFoster(${foster.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editFoster(${foster.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="assignAnimal(${foster.id})" class="text-green-600 hover:text-green-900 mr-3">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                    <button onclick="deleteFoster(${foster.id})" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    function getStatusColor(status) {
        const colors = {
            'active': 'bg-green-100 text-green-800',
            'inactive': 'bg-gray-100 text-gray-800',
            'pending': 'bg-yellow-100 text-yellow-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    function updateStats(fosters) {
        const activeCount = fosters.filter(f => f.status === 'active').length;
        const totalFurryFriends = fosters.reduce((sum, f) => sum + (f.active_assignments?.length || 0), 0);
        const totalCapacity = fosters.reduce((sum, f) => sum + f.capacity, 0);
        const availableCapacity = totalCapacity - totalFurryFriends;
        
        document.getElementById('activeCount').textContent = activeCount;
        document.getElementById('animalCount').textContent = totalFurryFriends;
        document.getElementById('capacityCount').textContent = availableCapacity;
    }
    
    document.getElementById('statusFilter').addEventListener('change', loadFosters);
    
    loadFosters();
</script>
@endsection

