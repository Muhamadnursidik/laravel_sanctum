<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\field;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['field', 'user'])->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'List Bookings',
        ], 200);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id'   => 'required|exists:fields,id',
            'user_id'    => 'required|exists:users,id',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'status'     => 'required|in:pending,success,failed',
        ]);

        $validated['start_time'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $validated['start_time']));
        $validated['end_time']   = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $validated['end_time']));

        $booking = Booking::create($validated);
        return response()->json([
            'message' => 'Booking created successfully',
            'data'    => $booking,
        ], 201);
    }

    // Tampilkan satu data
    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'field_id'   => 'sometimes|exists:fields,id',
            'user_id'    => 'sometimes|exists:users,id',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time'   => 'sometimes|date_format:H:i|after:start_time',
            'status'     => 'sometimes|in:pending,success,failed',
        ]);

        $booking->update($validated);
        return response()->json([
            'message' => 'Booking updated successfully',
            'data'    => $booking,
        ]);
    }

    // Hapus data
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
