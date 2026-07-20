<?php

namespace Database\Factories;

use App\Models\Shifts;
use App\Models\Teams;
use App\Models\Roles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'username'          => fake()->unique()->userName(),
            'position'          => fake()->randomElement(['Operator', 'Supervisor', 'IT', 'Manager']),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),

            // ambil ID random dari data yang SUDAH ADA, bukan bikin baru
            'role_id' => Roles::whereIn('id', [2, 3])->inRandomOrder()->value('id'),
            'team_id'  => Teams::inRandomOrder()->value('id'),
            'shift_id' => Shifts::inRandomOrder()->value('id'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}