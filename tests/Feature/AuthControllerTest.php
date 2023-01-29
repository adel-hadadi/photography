<?php

namespace Tests\Feature;

use App\Models\Auth\Otp;
use App\Models\User;
use Database\Factories\OtpFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCustomerRegister()
    {
        $phoneNumber = '9916197127';
        $response = $this->post('api/send-code', ['phone_number' => $phoneNumber]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->where('user.phone_number', '9916197127');
            $json->has('user.id');
            $json->etc();
        });

        $user = User::where('phone_number', $phoneNumber)->first();

        $code = $user->otpCodes()->latest()->where('used_at', null)->first();
        $response = $this->post("api/confirm-code/{$user->id}", ['code' => $code->code]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('user.token');
            $json->etc();
        });
    }

    public function testInvalidOtpCode()
    {
        $user = User::factory()->create();

        $response = $this->post("api/confirm-code/{$user->id}", [
            'code' => 123456
        ]);

        $response->assertStatus(400)->assertJson(function (AssertableJson $json) {
            $json->where('code', 'InvalidOtpCode');
            $json->etc();
        });
    }

    public function testOtpCodeIsRequired()
    {
        $user = User::factory()->create();

        $response = $this->post("api/confirm-code/{$user->id}", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('errors.code');
            $json->etc();
        })->assertStatus(422);
    }
}
