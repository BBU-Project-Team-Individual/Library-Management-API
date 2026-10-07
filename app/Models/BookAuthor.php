<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookAuthor extends Model
{
    protected $table = 'tblBookAuthor';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['BookID', 'AuthorID', 'AuthorDate', 'Remark'];

    protected function casts(): array
    {
        return ['AuthorDate' => 'date'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'BookID', 'BookID');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'AuthorID', 'AuthorID');
    }
}
