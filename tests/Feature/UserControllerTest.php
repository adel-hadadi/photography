<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Webmozart\Assert\Assert;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanUpdateProfile()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('api/edit-profile', [
            'first_name' => 'adel',
            'last_name' => 'hadadi',
            'avatar' => UploadedFile::fake()->image('image1.png')
        ]);

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('user.first_name')
                ->has('user.last_name')
                ->has('user.phone_number')
                ->has('user.avatar')
                ->missing('user.type');
            $json->etc();
        });

        $this->assertTrue(file_exists(public_path('images' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'image1.png')));
    }
}
