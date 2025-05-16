<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_income',
        'image',
        'description',
        'source_of_income',
        'total',
        'categories_id',
        'no_invoice'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

}
