<?php

namespace Database\Factories;

use App\Models\Shifts;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shifts>
 */
class ShiftsFactory extends Factory
{
    protected $model = Shifts::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $shiftNumber = 1;

        $start = now()->setTime(6 + $shiftNumber, 0);
        $finish = $start->copy()->addHours(8);

        return [
            'name'        => 'Shift ' . $shiftNumber++,
            'start_at'    => $start->format('H:i'),
            'finished_at' => $finish->format('H:i'),
        ];
    }
}
