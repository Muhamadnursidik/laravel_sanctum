<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPayment;
use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
    // Ambil semua data
    public function index()
    {
        $payments = BookingPayment::with('booking')->get();
        return response()->json($payments);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id'      => 'required|exists:bookings,id',
            'payment_method'  => 'required|in:cash,transfer,gateway',
            'payment_status'  => 'required|in:pending,success,failed',
            'amount'          => 'required|integer|min:0',
        ]);

        $payment = BookingPayment::create($validated);
        return response()->json([
            'message' => 'Booking payment created successfully',
            'data'    => $payment
        ], 201);
    }

    // Tampilkan satu data
    public function show($id)
    {
        $payment = BookingPayment::with('booking')->findOrFail($id);
        return response()->json($payment);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $payment = BookingPayment::findOrFail($id);

        $validated = $request->validate([
            'booking_id'      => 'sometimes|exists:bookings,id',
            'payment_method'  => 'sometimes|in:cash,transfer,gateway',
            'payment_status'  => 'sometimes|in:pending,success,failed',
            'amount'          => 'sometimes|integer|min:0',
        ]);

        $payment->update($validated);
        return response()->json([
            'message' => 'Booking payment updated successfully',
            'data'    => $payment
        ]);
    }

    // Hapus data
    public function destroy($id)
    {
        $payment = BookingPayment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Booking payment deleted successfully']);
    }
}
