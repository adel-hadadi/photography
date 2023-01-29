<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OtpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => rand(111111, 999999),
        ];
    }

    public function user(User $user)
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => $user->id
            ];
        });
    }
}
