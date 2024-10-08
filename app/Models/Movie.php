<?php

namespace App\Models;

use App\MovieStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        "status" => MovieStatus::class
    ];

    public function answers(){
        return $this->hasMany(MovieAnswer::class);
    }

    public function storyBoards(){
        return $this->hasMany(MovieStoryBoard::class);
    }
}
