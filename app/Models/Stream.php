<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'tokens_price',
        'type_id',
        'date_expiration',
    ];

    public function type()
    {
        return $this->belongsTo(StreamType::class, 'type_id');
    }
}
