<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\User;
use http\Exception\BadMessageException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'picture_count' => random_int(1, 5),
            'reservation_time' => now()->addDay(),
            'status' => ReservationStatus::PHOTOGRAPHER_WAITING->value
        ];
    }

    public function withUser(User $user):static
    {
        return $this->state(fn () => [
            'user_id' => $user->id
        ]);
    }

    public function withPhotographer(User $photographer):static
    {
        return $this->state(fn () => [
            'photographer_id' => $photographer->id
        ]);
    }
}
