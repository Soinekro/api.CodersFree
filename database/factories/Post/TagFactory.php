<?php

namespace Database\Factories\Post;

use App\Models\Post\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->word(20);
        return [
            'name'=>$name,
            'slug'=>Str::slug($name),
        ];
    }
}
