<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'squares', 'user_id'];

    protected $casts = [
        'squares' => 'array'
    ];
}
