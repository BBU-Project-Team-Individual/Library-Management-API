<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Book::with(['bookType', 'authors'])->latest('BookID')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $book = Book::create($request->validate($this->rules()));

        return response()->json($book->load('bookType'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json($book->load(['bookType', 'authors']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $book->update($request->validate($this->rules(true)));

        return response()->json($book->fresh()->load('bookType'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully.']);
    }

    private function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return [
            'BookTitle' => [$required, 'string', 'max:255'], 'BookTypeID' => [$required, 'integer', 'exists:tblBookType,BookTypeID'],
            'PublishDate' => ['sometimes', 'nullable', 'date'], 'NumOfPages' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'NumOfCopies' => ['sometimes', 'nullable', 'integer', 'min:0'], 'Edition' => ['sometimes', 'nullable', 'string', 'max:100'],
            'Publisher' => ['sometimes', 'nullable', 'string', 'max:150'], 'BookSource' => ['sometimes', 'nullable', 'string', 'max:255'],
            'Remark' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
