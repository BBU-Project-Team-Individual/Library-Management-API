<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;

it('supports the complete author, book type, book, and book-author CRUD flow', function () {
    $authorResponse = $this->postJson('/api/authors', [
        'AuthorName' => 'Gabriel Garcia Marquez',
        'Gender' => 'Male',
        'Email' => 'gabriel@example.com',
    ]);

    $authorResponse->assertCreated();
    $author = Author::query()->firstOrFail();

    $bookTypeResponse = $this->postJson('/api/book-types', ['BookTypeName' => 'Novel']);
    $bookTypeResponse->assertCreated();
    $bookType = BookType::query()->firstOrFail();

    $bookResponse = $this->postJson('/api/books', [
        'BookTitle' => 'One Hundred Years of Solitude',
        'BookTypeID' => $bookType->BookTypeID,
        'NumOfPages' => 417,
        'NumOfCopies' => 3,
    ]);

    $bookResponse->assertCreated();
    $book = Book::query()->firstOrFail();

    $linkResponse = $this->postJson('/api/book-authors', [
        'BookID' => $book->BookID,
        'AuthorID' => $author->AuthorID,
        'AuthorDate' => '1967-05-30',
    ]);

    $linkResponse->assertCreated();
    $this->assertDatabaseHas('tblBookAuthor', [
        'BookID' => $book->BookID,
        'AuthorID' => $author->AuthorID,
    ]);

    $this->putJson('/api/books/'.$book->BookID, ['BookTitle' => 'One Hundred Years'])->assertOk();
    $this->assertDatabaseHas('tblBook', ['BookID' => $book->BookID, 'BookTitle' => 'One Hundred Years']);
    $this->getJson('/api/books/'.$book->BookID)->assertOk()->assertJsonPath('BookTitle', 'One Hundred Years');

    $this->deleteJson('/api/book-authors/'.$book->BookID.'/'.$author->AuthorID)->assertOk();
    $this->deleteJson('/api/books/'.$book->BookID)->assertOk();
    $this->deleteJson('/api/authors/'.$author->AuthorID)->assertOk();
    $this->deleteJson('/api/book-types/'.$bookType->BookTypeID)->assertOk();
});

it('rejects a book when its book type does not exist', function () {
    $this->postJson('/api/books', [
        'BookTitle' => 'Invalid Book',
        'BookTypeID' => 999,
    ])->assertUnprocessable()->assertJsonValidationErrors(['BookTypeID']);
});
