<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with('donor');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('donation_type')) {
            $query->where('donation_type', $request->donation_type);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }
        
        $donations = $query->orderBy('donation_date', 'desc')->paginate(20);
        
        return response()->json($donations);
    }

    public function show($id)
    {
        $donation = Donation::with('donor')->findOrFail($id);
        
        return response()->json($donation);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donor_id' => 'required|exists:donors,id',
            'amount' => 'required|numeric|min:0',
            'donation_type' => 'required|in:monetary,supplies,services',
            'payment_method' => 'required|in:credit_card,paypal,check,cash',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $donation = Donation::create($request->all());
        
        return response()->json($donation->load('donor'), 201);
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'donor_id' => 'sometimes|exists:donors,id',
            'amount' => 'sometimes|numeric|min:0',
            'donation_type' => 'sometimes|in:monetary,supplies,services',
            'payment_method' => 'sometimes|in:credit_card,paypal,check,cash',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'donation_date' => 'sometimes|date',
            'tax_receipt_sent' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $donation->update($request->all());
        
        return response()->json($donation->load('donor'));
    }

    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();
        
        return response()->json(['message' => 'Donation deleted successfully'], 200);
    }

    public function sendReceipt($id)
    {
        $donation = Donation::findOrFail($id);
        
        if ($donation->status !== 'completed') {
            return response()->json(['error' => 'Can only send receipts for completed donations'], 400);
        }
        
        $donation->markReceiptSent();
        
        // Here you would implement actual receipt sending logic (email, PDF, etc.)
        
        return response()->json(['message' => 'Receipt sent successfully', 'donation' => $donation]);
    }
}

