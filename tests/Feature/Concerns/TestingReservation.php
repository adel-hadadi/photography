<?php


namespace Tests\Feature\Concerns;


use App\Models\User;

trait TestingReservation
{
    public function makeUserAndPhotographer(): array
    {
        $user = User::factory()->create();
        $photographer = User::factory()->photographer()->phoneNumber('9104443804')->create();

        return [$user, $photographer];
    }
}
