<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "story_boards" => "array",
        "metadata" => "json"
    ];
}
