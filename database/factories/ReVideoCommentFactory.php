<?php

namespace Database\Factories;

use App\Models\ReVideoComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReVideoCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReVideoComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->realText(random_int(1, 200)),
            'video_comment_id' => random_int(1, 100),
            'user_id' => random_int(1, 100),
        ];
    }
}
