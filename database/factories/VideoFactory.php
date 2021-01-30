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
            'thumbnail' => '/images/dummyData/dummy_thumbnail.jpeg',
            'thumbnail_name' => $this->faker->text(40).'.jpg',
            'video' => '/images/dummyData/dummy_video.qt',
            'video_name' => $this->faker->text(40).'mov',
            'video_time' => '5',
            'created_at' => DateTime::dateTimeThisDecade(),
            'updated_at' => DateTime::dateTimeThisDecade(),
        ];
    }
}
