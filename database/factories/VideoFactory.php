<?php

namespace Database\Factories;

use App\Models\Video;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(50),
            'about' => $this->faker->realText(200),
            'status' => $this->faker->randomElement(['normal', 'premium']),
            'thumbnail' => 'http://127.0.0.1:8000/storage/thumbnails/A_thumbnail_sample.jpeg',
            'thumbnail_name' => $this->faker->text(40).'.jpg',
            'video' => 'http://127.0.0.1:8000/storage/videos/A_video_sample.qt',
            'video_name' => $this->faker->text(40).'mov',
            'video_time' => '5',
            'created_at' => DateTime::dateTimeThisDecade(),
            'updated_at' => DateTime::dateTimeThisDecade(),
        ];
    }
}
