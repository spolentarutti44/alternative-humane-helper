<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;

class AnimalController extends Controller
{
    public function index()
    {
        return view('admin.animals.index');
    }

    public function show($id)
    {
        $animal = Animal::with(['fosterAssignments.foster', 'schedules'])->findOrFail($id);
        return view('admin.animals.show', compact('animal'));
    }
}

