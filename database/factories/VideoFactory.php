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
            'thumbnail' => $this->faker->imageUrl(),
            'video' => 'http://127.0.0.1:8000/storage/videos/aevu5gLVruv6ZN1xIBIR5AtEKFpFTCpqG8oTjH4U.qt',
            'created_at' => DateTime::dateTimeThisDecade(),
            'updated_at' => DateTime::dateTimeThisDecade(),
        ];
    }
}
