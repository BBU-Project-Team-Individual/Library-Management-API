<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(BookAuthor::with(['book', 'author'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $bookAuthor = BookAuthor::create($request->validate($this->rules()));

        return response()->json($bookAuthor->load(['book', 'author']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $bookId, int $authorId): JsonResponse
    {
        return response()->json($this->find($bookId, $authorId)->load(['book', 'author']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $bookId, int $authorId): JsonResponse
    {
        $bookAuthor = $this->find($bookId, $authorId);
        $bookAuthor->update($request->validate([
            'AuthorDate' => ['sometimes', 'nullable', 'date'], 'Remark' => ['sometimes', 'nullable', 'string'],
        ]));

        return response()->json($bookAuthor->fresh()->load(['book', 'author']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $bookId, int $authorId): JsonResponse
    {
        DB::table('tblBookAuthor')
            ->where('BookID', $bookId)
            ->where('AuthorID', $authorId)
            ->delete();

        return response()->json(['message' => 'Book-author link deleted successfully.']);
    }

    private function rules(): array
    {
        return [
            'BookID' => ['required', 'integer', 'exists:tblBook,BookID'],
            'AuthorID' => ['required', 'integer', 'exists:tblAuthor,AuthorID'],
            'AuthorDate' => ['sometimes', 'nullable', 'date'], 'Remark' => ['sometimes', 'nullable', 'string'],
        ];
    }

    private function find(int $bookId, int $authorId): BookAuthor
    {
        return BookAuthor::where('BookID', $bookId)->where('AuthorID', $authorId)->firstOrFail();
    }
}
