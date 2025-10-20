<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::withCount('donations');
        
        if ($request->has('is_recurring')) {
            $query->where('is_recurring', $request->boolean('is_recurring'));
        }
        
        $donors = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($donors);
    }

    public function show($id)
    {
        $donor = Donor::with('donations')->findOrFail($id);
        
        return response()->json([
            'donor' => $donor,
            'total_donated' => $donor->total_donated,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $donor = Donor::create($request->all());
        
        return response()->json($donor, 201);
    }

    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $donor->update($request->all());
        
        return response()->json($donor);
    }

    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();
        
        return response()->json(['message' => 'Donor deleted successfully'], 200);
    }
}

