<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        return view('admin.events.index');
    }
}

