<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiTrait;
class Category extends Model
{
    protected $fillable =['name','slug'];

    protected $allowIncluded = ['posts','posts.user'];
    protected $allowFilter = ['id','name','slug'];
    protected $allowSort = ['id','name','slug'];

    use HasFactory, ApiTrait;
    //relacion post
    public function posts(){
        return $this->hasMany(Post::class);
    }

}
