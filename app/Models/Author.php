<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $table = 'tblAuthor';

    protected $primaryKey = 'AuthorID';

    public $timestamps = false;

    protected $fillable = [
        'AuthorName', 'Gender', 'DOB', 'POB', 'Address', 'Phone', 'Email', 'Photo',
    ];

    protected function casts(): array
    {
        return ['DOB' => 'date'];
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'tblBookAuthor', 'AuthorID', 'BookID')
            ->withPivot(['AuthorDate', 'Remark']);
    }
}
