<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->has('upcoming') && $request->boolean('upcoming')) {
            $query->upcoming();
        }
        
        $events = $query->orderBy('start_date', 'asc')->paginate(15);
        
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::with('registrations')->findOrFail($id);
        
        return response()->json([
            'event' => $event,
            'available_spots' => $event->available_spots,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:adoption_event,fundraiser,volunteer_training,community',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'registration_required' => 'sometimes|boolean',
            'status' => 'sometimes|in:draft,published,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::create($request->all());
        
        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'sometimes|in:adoption_event,fundraiser,volunteer_training,community',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'location' => 'sometimes|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'registration_required' => 'sometimes|boolean',
            'status' => 'sometimes|in:draft,published,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->update($request->all());
        
        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    public function registrations($id)
    {
        $event = Event::findOrFail($id);
        $registrations = $event->registrations;
        
        return response()->json($registrations);
    }

    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        if (!$event->hasCapacity()) {
            return response()->json(['error' => 'Event is at full capacity'], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'attendee_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $registration = EventRegistration::create(array_merge($request->all(), [
            'event_id' => $id,
        ]));
        
        return response()->json($registration->load('event'), 201);
    }

    public function updateRegistration(Request $request, $id)
    {
        $registration = EventRegistration::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'attendee_name' => 'sometimes|string|max:255',
            'attendee_email' => 'sometimes|email|max:255',
            'attendee_phone' => 'nullable|string|max:20',
            'attendance_status' => 'sometimes|in:registered,attended,no_show,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $registration->update($request->all());
        
        return response()->json($registration);
    }
}

