<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
class Category extends Model
{
    protected $fillable =['name','slug'];

    protected $allowIncluded = ['posts','posts.user'];
    protected $allowFilter = ['id','name','slug'];
    protected $allowSort = ['id','name','slug'];

    use HasFactory;
    //relacion post
    public function posts(){
        return $this->hasMany(Post::class);
    }
    ///queryscopes
    public function scopeIncluded(Builder $query){
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',',request('included'));//convertir a array la cadena de texto

        $allowIncluded = collect($this->allowIncluded);
        foreach($relations as $key => $relationship){
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }
    public function scopeFilter(Builder $query){

        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter,'LIKE','%' . $value . '%');
            }
        }
    }

    public function scopeSort(Builder $query){
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',',request('sort'));

        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $sortField) {
            $direcction = 'asc';
            if (substr($sortField,0,1) == '-') {
                $direcction = 'desc';
                $sortField = substr($sortField,1);
            }
            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField,$direcction);
            }
        }

    }
    public function scopeGetOrpaginate(Builder $query){
        if(request('perPage')){
            $perpage = intval(request('perPage'));
            if($perpage){
                return $query->paginate($perpage);
            }
        }

        return $query->get();
    }


}
