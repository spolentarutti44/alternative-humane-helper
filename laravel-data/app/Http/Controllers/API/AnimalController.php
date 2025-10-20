<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::with('currentFosterAssignment.foster');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('species')) {
            $query->where('species', $request->species);
        }
        
        $animals = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($animals);
    }

    public function show($id)
    {
        $animal = Animal::with(['fosterAssignments.foster', 'schedules'])->findOrFail($id);
        
        return response()->json($animal);
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

        $animal = Animal::create($request->all());
        
        return response()->json($animal, 201);
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);
        
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

        $animal->update($request->all());
        
        return response()->json($animal);
    }

    public function destroy($id)
    {
        $animal = Animal::findOrFail($id);
        $animal->delete();
        
        return response()->json(['message' => 'Animal deleted successfully'], 200);
    }
}

