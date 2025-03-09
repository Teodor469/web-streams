<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamType extends Model
{
    use HasFactory;

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }
}
