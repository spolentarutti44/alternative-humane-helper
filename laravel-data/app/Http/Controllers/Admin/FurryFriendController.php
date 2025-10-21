<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FurryFriend;

class FurryFriendController extends Controller
{
    public function index()
    {
        return view('admin.furry_friends.index');
    }

    public function show($id)
    {
        $furryFriend = FurryFriend::with(['fosterAssignments.foster', 'schedules'])->findOrFail($id);
        return view('admin.furry_friends.show', compact('furryFriend'));
    }
}

