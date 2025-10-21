@extends('layouts.admin')

@section('title', 'Furry Friends Management')
@section('page-title', 'Furry Friends')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-4">
        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Status</option>
            <option value="available">Available</option>
            <option value="fostered">Fostered</option>
            <option value="adopted">Adopted</option>
            <option value="medical">Medical</option>
        </select>
        <select id="speciesFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Species</option>
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="other">Other</option>
        </select>
    </div>
    <button onclick="openAddModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Add New Furry Friend
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Available</p>
                <p class="text-3xl font-bold text-green-600" id="availableCount">-</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Fostered</p>
                <p class="text-3xl font-bold text-blue-600" id="fosteredCount">-</p>
            </div>
            <i class="fas fa-home text-4xl text-blue-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Adopted</p>
                <p class="text-3xl font-bold text-purple-600" id="adoptedCount">-</p>
            </div>
            <i class="fas fa-heart text-4xl text-purple-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Medical</p>
                <p class="text-3xl font-bold text-red-600" id="medicalCount">-</p>
            </div>
            <i class="fas fa-medkit text-4xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Furry Friends Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intake Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody id="furryFriendsTableBody" class="bg-white divide-y divide-gray-200">
            <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
</div>

<div id="pagination" class="mt-6 flex justify-center"></div>
@endsection

@section('scripts')
<script>
    let currentPage = 1;
    
    async function loadFurryFriends(page = 1) {
        const status = document.getElementById('statusFilter').value;
        const species = document.getElementById('speciesFilter').value;
        
        let url = `/api/furry-friends?page=${page}`;
        if (status) url += `&status=${status}`;
        if (species) url += `&species=${species}`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        displayFurryFriends(data.data);
        updateStats();
    }
    
    function displayFurryFriends(furryFriends) {
        const tbody = document.getElementById('furryFriendsTableBody');
        tbody.innerHTML = furryFriends.map(furryFriend => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <img src="${furryFriend.photo_url || '/images/default-animal.png'}" 
                         class="h-12 w-12 rounded-full object-cover" alt="${furryFriend.name}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium">${furryFriend.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">${furryFriend.species}</td>
                <td class="px-6 py-4 whitespace-nowrap">${furryFriend.age || 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">${furryFriend.gender}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(furryFriend.status)}">
                        ${furryFriend.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${furryFriend.intake_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="viewFurryFriend(${furryFriend.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editFurryFriend(${furryFriend.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteFurryFriend(${furryFriend.id})" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    function getStatusColor(status) {
        const colors = {
            'available': 'bg-green-100 text-green-800',
            'fostered': 'bg-blue-100 text-blue-800',
            'adopted': 'bg-purple-100 text-purple-800',
            'medical': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    async function updateStats() {
        const response = await fetch('/api/furry-friends');
        const data = await response.json();
        const furryFriends = data.data;
        
        document.getElementById('availableCount').textContent = furryFriends.filter(f => f.status === 'available').length;
        document.getElementById('fosteredCount').textContent = furryFriends.filter(f => f.status === 'fostered').length;
        document.getElementById('adoptedCount').textContent = furryFriends.filter(f => f.status === 'adopted').length;
        document.getElementById('medicalCount').textContent = furryFriends.filter(f => f.status === 'medical').length;
    }
    
    document.getElementById('statusFilter').addEventListener('change', () => loadFurryFriends());
    document.getElementById('speciesFilter').addEventListener('change', () => loadFurryFriends());
    
    // Load furry friends on page load
    loadFurryFriends();
</script>
@endsection

