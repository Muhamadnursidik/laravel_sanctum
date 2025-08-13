<?php

namespace App\Http\Controllers\Api;

use App\Models\location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $location = location::latest()->get();
        $res = [
            'success' => true,
            'data' => $location,
            'message' => 'List Locations',
        ];
        return response()->json($res, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'maps' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }
        $location = new location();
        $location->name = $request->name;
        $location->address = $request->address;
        $location->maps = $request->maps;
        $location->save();

        return response()->json([
            'data' => $location,
            'message' => 'Location created successfully',
            'success' => true
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $location = location::find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $location,
            'message' => 'Show Location detail',
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:locations,id,'. $id,
            'address' => 'required|string|max:255',
            'maps' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }

        $location = location::find($id);
        $location->name = $request->name;
        $location->address = $request->address;
        $location->maps = $request->maps;
        $location->save();

        $res = [
            'success' => true,
            'data' => $location,
            'message' => 'Location update successfully',
        ];
        return response()->json($res, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $location = location::find($id);
        if (!$location) {
            return response()->json([
                'message' => 'Data not found',

            ], 404);
        }

        $location->delete();

        $res = [
            'success' => true,
            'message' => 'Location deleted successfully',
        ];
        return response()->json($res, 200);
    }
}
