<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Foster;
use App\Models\FosterAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FosterController extends Controller
{
    public function index(Request $request)
    {
        $query = Foster::with('activeAssignments.animal');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $fosters = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($fosters);
    }

    public function show($id)
    {
        $foster = Foster::with(['assignments.animal', 'schedules'])->findOrFail($id);
        
        return response()->json($foster);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:fosters,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'sometimes|in:active,inactive,pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $foster = Foster::create($request->all());
        
        return response()->json($foster, 201);
    }

    public function update(Request $request, $id)
    {
        $foster = Foster::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:fosters,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:active,inactive,pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $foster->update($request->all());
        
        return response()->json($foster);
    }

    public function destroy($id)
    {
        $foster = Foster::findOrFail($id);
        $foster->delete();
        
        return response()->json(['message' => 'Foster deleted successfully'], 200);
    }

    public function assignments($id)
    {
        $foster = Foster::findOrFail($id);
        $assignments = $foster->assignments()->with('animal')->get();
        
        return response()->json($assignments);
    }

    public function assignAnimal(Request $request, $id)
    {
        $foster = Foster::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|exists:animals,id',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$foster->hasCapacity()) {
            return response()->json(['error' => 'Foster has reached capacity'], 400);
        }

        $assignment = FosterAssignment::create([
            'foster_id' => $id,
            'animal_id' => $request->animal_id,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        // Update animal status
        $assignment->animal->update(['status' => 'fostered']);
        
        return response()->json($assignment->load('animal'), 201);
    }
}

