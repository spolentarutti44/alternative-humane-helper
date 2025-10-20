<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Foster;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_animals' => Animal::count(),
            'available_animals' => Animal::where('status', 'available')->count(),
            'active_fosters' => Foster::where('status', 'active')->count(),
            'upcoming_events' => Event::upcoming()->count(),
            'total_donations' => Donation::completed()->sum('amount'),
            'upcoming_schedules' => Schedule::upcoming()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

