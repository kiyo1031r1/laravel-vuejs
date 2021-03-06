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
        $video_num = 100;
        $category_num = 9;
        $video_video_category_num = 200;
        $user_num = 100;
        $video_comment_num = 500;

        return [
            're_video_comment' => $this->faker->realText(random_int(11, 100)),
            'video_comment_id' => random_int(1, $video_comment_num),
            'user_id' => random_int(1, $user_num),
        ];
    }
}
