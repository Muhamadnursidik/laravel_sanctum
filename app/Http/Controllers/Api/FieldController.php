<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::with('location')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $fields,
            'message' => 'List Fields',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fields',
            'img' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|string|max:100',
            'price_per_hour' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $field = new Field();
        $field->name = $request->name;
        $field->location_id = $request->location_id;
        $field->type = $request->type;
        $field->price_per_hour = $request->price_per_hour;
        $field->description = $request->description;

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('fields', 'public');
            $field->img = $path;
        }

        $field->save();

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Field created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $field = Field::with('location')->find($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Show Field Detail',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|string|max:255|unique:fields,name,{$id}",
            'img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|string|max:100',
            'price_per_hour' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors(),
            ], 422);
        }

        $field = Field::find($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        $field->name = $request->name;
        $field->location_id = $request->location_id;
        $field->type = $request->type;
        $field->price_per_hour = $request->price_per_hour;
        $field->description = $request->description;

        if ($request->hasFile('img')) {
            if ($field->img && Storage::disk('public')->exists($field->img)) {
                Storage::disk('public')->delete($field->img);
            }
            $path = $request->file('img')->store('fields', 'public');
            $field->img = $path;
        }

        $field->save();

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Field updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $field = Field::find($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data not found',
            ], 404);
        }

        if ($field->img && Storage::disk('public')->exists($field->img)) {
            Storage::disk('public')->delete($field->img);
        }

        $field->delete();

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Field deleted successfully',
        ], 200);
    }
}
