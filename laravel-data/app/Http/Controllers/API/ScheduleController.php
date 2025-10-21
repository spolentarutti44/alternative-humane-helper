<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['furryFriend', 'foster', 'volunteer', 'creator']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }
        
        $schedules = $query->orderBy('start_time', 'asc')->paginate(20);
        
        return response()->json($schedules);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['furryFriend', 'foster', 'volunteer', 'creator'])->findOrFail($id);
        
        return response()->json($schedule);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:appointment,transport,medical,grooming',
            'furry_friend_id' => 'nullable|exists:furry_friends,id',
            'foster_id' => 'nullable|exists:fosters,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule = Schedule::create(array_merge($request->all(), [
            'created_by' => auth()->id() ?? 1, // Default to user 1 if not authenticated
        ]));
        
        return response()->json($schedule->load(['furryFriend', 'foster', 'volunteer']), 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:appointment,transport,medical,grooming',
            'furry_friend_id' => 'nullable|exists:furry_friends,id',
            'foster_id' => 'nullable|exists:fosters,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule->update($request->all());
        
        return response()->json($schedule->load(['furryFriend', 'foster', 'volunteer']));
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        
        return response()->json(['message' => 'Schedule deleted successfully'], 200);
    }
}

