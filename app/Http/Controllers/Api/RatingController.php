<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ratings = Rating::with(['user', 'field'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'List Ratings',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'field_id' => 'required|exists:fields,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $rating = Rating::create($request->only([
            'user_id', 'field_id', 'rating', 'review'
        ]));

        return response()->json([
            'success' => true,
            'data' => $rating,
            'message' => 'Rating created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rating = Rating::with(['user', 'field'])->find($id);

        if (!$rating) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rating,
            'message' => 'Show Rating Detail',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'field_id' => 'required|exists:fields,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        $rating->update($request->only([
            'user_id', 'field_id', 'rating', 'review'
        ]));

        return response()->json([
            'success' => true,
            'data' => $rating,
            'message' => 'Rating updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        $rating->delete();

        return response()->json([
            'success' => true,
            'data' => $rating,
            'message' => 'Rating deleted successfully',
        ], 200);
    }
}
