<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FurryFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FurryFriendController extends Controller
{
    public function index(Request $request)
    {
        $query = FurryFriend::with('currentFosterAssignment.foster');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('species')) {
            $query->where('species', $request->species);
        }
        
        $furryFriends = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($furryFriends);
    }

    public function show($id)
    {
        $furryFriend = FurryFriend::with(['fosterAssignments.foster', 'schedules'])->findOrFail($id);
        
        return response()->json($furryFriend);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'required|in:male,female,unknown',
            'status' => 'required|in:available,fostered,adopted,medical',
            'description' => 'nullable|string',
            'intake_date' => 'required|date',
            'medical_notes' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $furryFriend = FurryFriend::create($request->all());
        
        return response()->json($furryFriend, 201);
    }

    public function update(Request $request, $id)
    {
        $furryFriend = FurryFriend::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'species' => 'sometimes|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'sometimes|in:male,female,unknown',
            'status' => 'sometimes|in:available,fostered,adopted,medical',
            'description' => 'nullable|string',
            'intake_date' => 'sometimes|date',
            'medical_notes' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $furryFriend->update($request->all());
        
        return response()->json($furryFriend);
    }

    public function destroy($id)
    {
        $furryFriend = FurryFriend::findOrFail($id);
        $furryFriend->delete();
        
        return response()->json(['message' => 'Furry Friend deleted successfully'], 200);
    }
}

