<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Author::query()->latest('AuthorID')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $author = Author::create($request->validate([
            'AuthorName' => ['required', 'string', 'max:150'], 'Gender' => ['nullable', 'string', 'max:20'],
            'DOB' => ['nullable', 'date'], 'POB' => ['nullable', 'string', 'max:150'],
            'Address' => ['nullable', 'string', 'max:255'], 'Phone' => ['nullable', 'string', 'max:30'],
            'Email' => ['nullable', 'email', 'max:255'], 'Photo' => ['nullable', 'string', 'max:255'],
        ]));

        return response()->json($author, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author): JsonResponse
    {
        $author->update($request->validate([
            'AuthorName' => ['sometimes', 'required', 'string', 'max:150'], 'Gender' => ['sometimes', 'nullable', 'string', 'max:20'],
            'DOB' => ['sometimes', 'nullable', 'date'], 'POB' => ['sometimes', 'nullable', 'string', 'max:150'],
            'Address' => ['sometimes', 'nullable', 'string', 'max:255'], 'Phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'Email' => ['sometimes', 'nullable', 'email', 'max:255'], 'Photo' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]));

        return response()->json($author->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json(['message' => 'Author deleted successfully.']);
    }
}
