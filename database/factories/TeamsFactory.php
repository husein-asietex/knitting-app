<?php

namespace Database\Factories;

use App\Models\Teams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teams>
 */
class TeamsFactory extends Factory
{
    protected $model = Teams::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $teamNumber = 1;

        return [
            'name' => 'Team ' . $teamNumber++,
        ];
    }
}
