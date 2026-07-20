<?php

namespace Database\Factories;

use App\Models\MachineOperators;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MachineOperators>
 */
class MachineOperatorsFactory extends Factory
{
    protected $model = MachineOperators::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => fake()->name,
            'position'   => 'Operator',
            'team_id'    => fake()->numberBetween(1, 5),
            'shift_id'   => fake()->numberBetween(1, 3),
            'section_id' => fake()->numberBetween(1, 3),
        ];
    }
}
