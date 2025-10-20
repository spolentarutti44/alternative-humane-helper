@extends('layouts.admin')

@section('title', 'Event Management')
@section('page-title', 'Events')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-4">
        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select id="typeFilter" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">All Types</option>
            <option value="adoption_event">Adoption Event</option>
            <option value="fundraiser">Fundraiser</option>
            <option value="volunteer_training">Volunteer Training</option>
            <option value="community">Community</option>
        </select>
        <label class="flex items-center space-x-2">
            <input type="checkbox" id="upcomingOnly" class="rounded">
            <span class="text-sm">Upcoming Only</span>
        </label>
    </div>
    <button onclick="openAddModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Create Event
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Upcoming Events</p>
                <p class="text-3xl font-bold text-blue-600" id="upcomingCount">-</p>
            </div>
            <i class="fas fa-calendar-alt text-4xl text-blue-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Registrations</p>
                <p class="text-3xl font-bold text-green-600" id="registrationCount">-</p>
            </div>
            <i class="fas fa-users text-4xl text-green-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">This Month</p>
                <p class="text-3xl font-bold text-purple-600" id="monthCount">-</p>
            </div>
            <i class="fas fa-calendar-check text-4xl text-purple-200"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Completed</p>
                <p class="text-3xl font-bold text-gray-600" id="completedCount">-</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-gray-200"></i>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div id="eventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Dynamic event cards will be loaded here -->
</div>
@endsection

@section('scripts')
<script>
    async function loadEvents() {
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        const upcoming = document.getElementById('upcomingOnly').checked;
        
        let url = '/api/events';
        const params = [];
        if (status) params.push(`status=${status}`);
        if (type) params.push(`event_type=${type}`);
        if (upcoming) params.push(`upcoming=1`);
        if (params.length) url += '?' + params.join('&');
        
        const response = await fetch(url);
        const data = await response.json();
        
        displayEvents(data.data);
        updateStats(data.data);
    }
    
    function displayEvents(events) {
        const grid = document.getElementById('eventsGrid');
        grid.innerHTML = events.map(event => `
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 text-xs rounded-full ${getTypeColor(event.event_type)}">
                            ${formatEventType(event.event_type)}
                        </span>
                        <span class="px-3 py-1 text-xs rounded-full ${getStatusColor(event.status)}">
                            ${event.status}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-2">${event.title}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">${event.description || 'No description'}</p>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-indigo-600"></i>
                            ${new Date(event.start_date).toLocaleDateString()}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-indigo-600"></i>
                            ${new Date(event.start_date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>
                            ${event.location}
                        </div>
                        ${event.capacity ? `
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2 text-indigo-600"></i>
                            ${event.registrations_count || 0} / ${event.capacity} registered
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="flex space-x-2">
                        <button onclick="viewEvent(${event.id})" 
                                class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                        <button onclick="editEvent(${event.id})" 
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button onclick="manageRegistrations(${event.id})" 
                                class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                            <i class="fas fa-list mr-1"></i> Reg.
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    function formatEventType(type) {
        return type.split('_').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }
    
    function getTypeColor(type) {
        const colors = {
            'adoption_event': 'bg-purple-100 text-purple-800',
            'fundraiser': 'bg-green-100 text-green-800',
            'volunteer_training': 'bg-blue-100 text-blue-800',
            'community': 'bg-orange-100 text-orange-800'
        };
        return colors[type] || 'bg-gray-100 text-gray-800';
    }
    
    function getStatusColor(status) {
        const colors = {
            'published': 'bg-green-100 text-green-800',
            'draft': 'bg-gray-100 text-gray-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }
    
    function updateStats(events) {
        const now = new Date();
        const upcoming = events.filter(e => new Date(e.start_date) > now && e.status === 'published').length;
        const completed = events.filter(e => e.status === 'completed').length;
        const totalRegistrations = events.reduce((sum, e) => sum + (e.registrations_count || 0), 0);
        
        document.getElementById('upcomingCount').textContent = upcoming;
        document.getElementById('completedCount').textContent = completed;
        document.getElementById('registrationCount').textContent = totalRegistrations;
        document.getElementById('monthCount').textContent = events.length;
    }
    
    document.getElementById('statusFilter').addEventListener('change', loadEvents);
    document.getElementById('typeFilter').addEventListener('change', loadEvents);
    document.getElementById('upcomingOnly').addEventListener('change', loadEvents);
    
    loadEvents();
</script>
@endsection

