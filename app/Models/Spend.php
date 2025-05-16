<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spend extends Model
{
    use HasFactory;

    protected $table = 'spendings';

    protected $fillable = [
        'date_spendings',
        'image',
        'description',
        'source_of_spending',
        'total',
        'categories_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
