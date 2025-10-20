<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nonprofit Helper Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">🐾 Nonprofit Helper</h1>
                <p class="text-xs text-indigo-200 mt-1">Admin Dashboard</p>
            </div>
            
            <nav class="mt-6">
                <a href="/admin/dashboard" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/dashboard') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="/admin/animals" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/animals*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-paw mr-3"></i>
                    Animals
                </a>
                <a href="/admin/fosters" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/fosters*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Fosters
                </a>
                <a href="/admin/schedules" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/schedules*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-calendar mr-3"></i>
                    Scheduler
                </a>
                <a href="/admin/donations" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/donations*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-hand-holding-heart mr-3"></i>
                    Donations
                </a>
                <a href="/admin/events" class="flex items-center px-6 py-3 hover:bg-indigo-800 transition {{ request()->is('admin/events*') ? 'bg-indigo-800 border-l-4 border-white' : '' }}">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Events
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <div class="bg-indigo-800 rounded-lg p-4">
                    <p class="text-sm">Logged in as:</p>
                    <p class="font-bold">Admin User</p>
                    <form action="/logout" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="text-xs text-indigo-200 hover:text-white">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div class="text-sm text-gray-600">
                            {{ date('l, F j, Y') }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>

