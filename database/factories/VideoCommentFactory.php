<?php

namespace Database\Factories;

use App\Models\VideoComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->realText(random_int(11, 100)),
            'video_id' => random_int(1, 100),
            'user_id' => random_int(1, 100),
        ];
    }
}
