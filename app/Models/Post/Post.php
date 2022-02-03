<?php

namespace App\Models\Post;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    //relacion 1 a muchios inv

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        $this->belongsTo(Category::class);
    }

    //relacion muchos a muchos

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    //relacion 1 a muchos polimorfica

    public function images(){
        return $this->morphToMany(Image::class,'imageable');
    }
}
