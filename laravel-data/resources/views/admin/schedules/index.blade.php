@extends('layouts.admin')

@section('title', 'Schedule Management')
@section('page-title', 'Scheduler')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-4">
        <select id="typeFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Types</option>
            <option value="appointment">Appointment</option>
            <option value="transport">Transport</option>
            <option value="medical">Medical</option>
            <option value="grooming">Grooming</option>
        </select>
        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Status</option>
            <option value="scheduled">Scheduled</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="date" id="dateFilter" class="border border-gray-300 rounded-lg px-4 py-2">
    </div>
    <button onclick="openAddModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Add Schedule
    </button>
</div>

<!-- Calendar View Toggle -->
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <div class="flex space-x-4">
        <button onclick="switchView('calendar')" id="calendarViewBtn" 
                class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg">
            <i class="fas fa-calendar mr-2"></i>Calendar View
        </button>
        <button onclick="switchView('list')" id="listViewBtn" 
                class="px-4 py-2 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-list mr-2"></i>List View
        </button>
    </div>
</div>

<!-- Calendar View (Simple) -->
<div id="calendarView" class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-7 gap-4">
        <div class="text-center font-bold text-gray-600">Sun</div>
        <div class="text-center font-bold text-gray-600">Mon</div>
        <div class="text-center font-bold text-gray-600">Tue</div>
        <div class="text-center font-bold text-gray-600">Wed</div>
        <div class="text-center font-bold text-gray-600">Thu</div>
        <div class="text-center font-bold text-gray-600">Fri</div>
        <div class="text-center font-bold text-gray-600">Sat</div>
    </div>
    <div id="calendarGrid" class="grid grid-cols-7 gap-4 mt-4">
        <!-- Calendar cells will be generated here -->
    </div>
</div>

<!-- List View -->
<div id="listView" class="hidden bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody id="schedulesTableBody" class="bg-white divide-y divide-gray-200">
            <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    let currentView = 'calendar';
    
    function switchView(view) {
        currentView = view;
        if (view === 'calendar') {
            document.getElementById('calendarView').classList.remove('hidden');
            document.getElementById('listView').classList.add('hidden');
            document.getElementById('calendarViewBtn').classList.add('bg-indigo-100', 'text-indigo-700');
            document.getElementById('listViewBtn').classList.remove('bg-indigo-100', 'text-indigo-700');
        } else {
            document.getElementById('calendarView').classList.add('hidden');
            document.getElementById('listView').classList.remove('hidden');
            document.getElementById('listViewBtn').classList.add('bg-indigo-100', 'text-indigo-700');
            document.getElementById('calendarViewBtn').classList.remove('bg-indigo-100', 'text-indigo-700');
            loadSchedules();
        }
    }
    
    async function loadSchedules() {
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        let url = '/api/schedules';
        const params = [];
        if (type) params.push(`type=${type}`);
        if (status) params.push(`status=${status}`);
        if (params.length) url += '?' + params.join('&');
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (currentView === 'list') {
            displaySchedules(data.data);
        }
    }
    
    function displaySchedules(schedules) {
        const tbody = document.getElementById('schedulesTableBody');
        tbody.innerHTML = schedules.map(schedule => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap font-medium">${schedule.title}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">${schedule.type}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${new Date(schedule.start_time).toLocaleString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${schedule.location || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${schedule.animal ? schedule.animal.name : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(schedule.status)}">
                        ${schedule.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="viewSchedule(${schedule.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editSchedule(${schedule.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteSchedule(${schedule.id})" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    function getStatusColor(status) {
        const colors = {
            'scheduled': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    document.getElementById('typeFilter').addEventListener('change', loadSchedules);
    document.getElementById('statusFilter').addEventListener('change', loadSchedules);
</script>
@endsection

