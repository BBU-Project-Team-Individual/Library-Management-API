<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookAuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('authors', AuthorController::class);
Route::apiResource('book-types', BookTypeController::class)->parameters(['book-types' => 'bookType']);
Route::apiResource('books', BookController::class);

Route::get('book-authors', [BookAuthorController::class, 'index']);
Route::post('book-authors', [BookAuthorController::class, 'store']);
Route::get('book-authors/{bookId}/{authorId}', [BookAuthorController::class, 'show']);
Route::match(['put', 'patch'], 'book-authors/{bookId}/{authorId}', [BookAuthorController::class, 'update']);
Route::delete('book-authors/{bookId}/{authorId}', [BookAuthorController::class, 'destroy']);
