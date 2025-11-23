<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Initiative extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'start_date',
        'end_date',
        'impact_score',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
