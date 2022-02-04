<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Category;
use Illuminate\Http\Request;

use App\Http\Resources\Post\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::included()
        ->filter()
        ->sort()
        ->getOrpaginate();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|max:255',
            'slug'=> 'required|max:255|unique:categories'
        ]);

       $category = Category::create($request->all());

       return CategoryResource::make($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $category =Category::included()->findorFail($id);
        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\post\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=> 'required|max:255',
            'slug'=> 'required|max:255|unique:categories,slug,' . $category->id,
        ]);

       $category->update($request->all());

       return CategoryResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\post\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return CategoryResource::make($category);
    }
}
