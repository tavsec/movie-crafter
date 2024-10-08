<?php

namespace App\Models;

use App\Services\Pinata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieStoryBoard extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getSignedUrl(){
        return app()->make(Pinata::class)->getSignedUrl($this->pinata_cid);
    }
}
