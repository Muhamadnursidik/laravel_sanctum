<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'field'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
            'message' => 'List Payments',
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'field_id' => 'required|exists:fields,id',
            'bookking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_price' => 'required|integer|min:0',
            'status' => 'nullable|in:avaible,pending,paid,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $payment = Payment::create($request->only([
            'user_id', 'field_id', 'bookking_date', 'start_time', 'end_time', 'total_price', 'status'
        ]));

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment created successfully',
        ], 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['user', 'field'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Show Payment Detail',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'field_id' => 'required|exists:fields,id',
            'bookking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_price' => 'required|integer|min:0',
            'status' => 'nullable|in:avaible,pending,paid,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        $payment->update($request->only([
            'user_id', 'field_id', 'bookking_date', 'start_time', 'end_time', 'total_price', 'status'
        ]));

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment updated successfully',
        ], 200);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment deleted successfully',
        ], 200);
    }
}
