<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Foster;

class FosterController extends Controller
{
    public function index()
    {
        return view('admin.fosters.index');
    }

    public function show($id)
    {
        $foster = Foster::with(['assignments.animal', 'schedules'])->findOrFail($id);
        return view('admin.fosters.show', compact('foster'));
    }
}

