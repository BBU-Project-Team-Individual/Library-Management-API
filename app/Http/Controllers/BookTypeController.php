<?php

namespace App\Http\Controllers;

use App\Models\BookType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(BookType::query()->latest('BookTypeID')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $bookType = BookType::create($request->validate(['BookTypeName' => ['required', 'string', 'max:100']]));

        return response()->json($bookType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BookType $bookType): JsonResponse
    {
        return response()->json($bookType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookType $bookType): JsonResponse
    {
        $bookType->update($request->validate(['BookTypeName' => ['sometimes', 'required', 'string', 'max:100']]));

        return response()->json($bookType->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookType $bookType): JsonResponse
    {
        $bookType->delete();

        return response()->json(['message' => 'Book type deleted successfully.']);
    }
}
