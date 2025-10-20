@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
    <h1 class="text-3xl font-bold mb-2">Welcome to Nonprofit Helper</h1>
    <p class="text-indigo-100">Manage your animal shelter operations efficiently</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Animals Stats -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Total Animals</p>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['total_animals'] ?? 0 }}</p>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-check-circle"></i>
                    {{ $stats['available_animals'] ?? 0 }} Available
                </p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-paw text-3xl text-green-600"></i>
            </div>
        </div>
        <a href="/admin/animals" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 inline-block">
            View all animals →
        </a>
    </div>

    <!-- Foster Stats -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Active Fosters</p>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['active_fosters'] ?? 0 }}</p>
                <p class="text-sm text-blue-600 mt-2">
                    <i class="fas fa-home"></i>
                    Foster families
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-users text-3xl text-blue-600"></i>
            </div>
        </div>
        <a href="/admin/fosters" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 inline-block">
            View all fosters →
        </a>
    </div>

    <!-- Donation Stats -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Total Donations</p>
                <p class="text-4xl font-bold text-gray-800 mt-2">${{ number_format($stats['total_donations'] ?? 0, 2) }}</p>
                <p class="text-sm text-purple-600 mt-2">
                    <i class="fas fa-heart"></i>
                    All time
                </p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-hand-holding-heart text-3xl text-purple-600"></i>
            </div>
        </div>
        <a href="/admin/donations" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 inline-block">
            View all donations →
        </a>
    </div>

    <!-- Events Stats -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Upcoming Events</p>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['upcoming_events'] ?? 0 }}</p>
                <p class="text-sm text-orange-600 mt-2">
                    <i class="fas fa-calendar-check"></i>
                    Scheduled
                </p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-calendar-alt text-3xl text-orange-600"></i>
            </div>
        </div>
        <a href="/admin/events" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 inline-block">
            View all events →
        </a>
    </div>

    <!-- Schedule Stats -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Upcoming Tasks</p>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['upcoming_schedules'] ?? 0 }}</p>
                <p class="text-sm text-red-600 mt-2">
                    <i class="fas fa-clock"></i>
                    Scheduled
                </p>
            </div>
            <div class="bg-red-100 rounded-full p-4">
                <i class="fas fa-calendar text-3xl text-red-600"></i>
            </div>
        </div>
        <a href="/admin/schedules" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 inline-block">
            View scheduler →
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-gray-500">
        <p class="text-gray-500 text-sm font-medium uppercase mb-4">Quick Actions</p>
        <div class="space-y-3">
            <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-left">
                <i class="fas fa-plus mr-2"></i>Add New Animal
            </button>
            <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-left">
                <i class="fas fa-calendar-plus mr-2"></i>Schedule Event
            </button>
            <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-left">
                <i class="fas fa-dollar-sign mr-2"></i>Record Donation
            </button>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Animals -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-paw text-indigo-600 mr-2"></i>
            Recent Animals
        </h3>
        <div id="recentAnimals" class="space-y-3">
            <p class="text-gray-500 text-sm">Loading...</p>
        </div>
    </div>

    <!-- Upcoming Schedules -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-calendar text-indigo-600 mr-2"></i>
            Upcoming Schedule
        </h3>
        <div id="upcomingSchedules" class="space-y-3">
            <p class="text-gray-500 text-sm">Loading...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function loadRecentAnimals() {
        const response = await fetch('/api/animals?per_page=5');
        const data = await response.json();
        const container = document.getElementById('recentAnimals');
        
        if (data.data && data.data.length > 0) {
            container.innerHTML = data.data.map(animal => `
                <div class="flex items-center justify-between border-b pb-3">
                    <div class="flex items-center">
                        <img src="${animal.photo_url || '/images/default-animal.png'}" 
                             class="h-10 w-10 rounded-full object-cover mr-3" alt="${animal.name}">
                        <div>
                            <p class="font-medium">${animal.name}</p>
                            <p class="text-xs text-gray-500">${animal.species} • ${animal.status}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full ${getStatusColor(animal.status)}">
                        ${animal.status}
                    </span>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-gray-500 text-sm">No animals yet</p>';
        }
    }
    
    async function loadUpcomingSchedules() {
        const response = await fetch('/api/schedules?status=scheduled&per_page=5');
        const data = await response.json();
        const container = document.getElementById('upcomingSchedules');
        
        if (data.data && data.data.length > 0) {
            container.innerHTML = data.data.map(schedule => `
                <div class="border-b pb-3">
                    <p class="font-medium">${schedule.title}</p>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        ${new Date(schedule.start_time).toLocaleString()}
                    </p>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-gray-500 text-sm">No upcoming schedules</p>';
        }
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
    
    loadRecentAnimals();
    loadUpcomingSchedules();
</script>
@endsection

