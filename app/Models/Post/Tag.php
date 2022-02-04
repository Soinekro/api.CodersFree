<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiTrait;

class Tag extends Model
{
    use HasFactory, ApiTrait;

    public function posts(){
        return $this->belongsToMany(Post::class);
    }
}
