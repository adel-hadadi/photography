<?php

namespace Tests\Feature;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Concerns\TestingReservation;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase, TestingReservation;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanSendReservationRequest()
    {
        [$user, $photographer] = $this->makeUserAndPhotographer();
        Sanctum::actingAs($user);

        $response = $this->post("api/send-reservation-request/{$photographer->id}", [
            'picture_count' => 3,
            'reservation_time' => now()->addDays(3)
        ]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) use ($photographer) {
            $json->has('picture_count')
                ->has('reservation_time')
                ->where('photographer.phone_number', $photographer->phone_number)
                ->where('status', ReservationStatus::PHOTOGRAPHER_WAITING->value);
            $json->etc();
        });
    }

    public function testInvalidReservation()
    {
        [$user, $photographer] = $this->makeUserAndPhotographer();
        Sanctum::actingAs($photographer);

        $response = $this->post("api/send-reservation-request/{$user->id}", [
            'picture_count' => 3,
            'reservation_time' => now()->addDay()
        ]);

        $response->assertStatus(400)->assertJson(function (AssertableJson $json) {
            $json->where('code', 'CanNotSendReservationRequest');
            $json->etc();
        });
    }

    public function testPhotographerAcceptReservation()
    {
        [$user, $photographer] = $this->makeUserAndPhotographer();
        Sanctum::actingAs($photographer);
        $reservation = Reservation::factory()->withUser($user)->withPhotographer($photographer)->create();

        $response = $this->get("api/accept-reservation/{$reservation->id}");

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->where('status', ReservationStatus::ACCEPTED->value);
            $json->etc();
        });
    }

    public function testInvalidPhotographerCantAcceptReservation()
    {
        [$user, $photographer] = $this->makeUserAndPhotographer();
        $reservation = Reservation::factory()->withUser($user)->withPhotographer($photographer)->create();
        $secondPhotographer = User::factory()->photographer()->create();

        Sanctum::actingAs($secondPhotographer);

        $response = $this->get("api/accept-reservation/{$reservation->id}");

        $response->assertStatus(400)->assertJson(function (AssertableJson $json) {
            $json->where('code', 'CantAcceptReservation');
            $json->etc();
        });

        Sanctum::actingAs($user);
        $response = $this->get("api/accept-reservation/{$reservation->id}");

        $response->assertStatus(400)->assertJson(function (AssertableJson $json) {
            $json->where('code', 'CantAcceptReservation');
            $json->etc();
        });
    }
}
