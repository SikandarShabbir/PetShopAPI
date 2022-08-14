<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_checking_if_the_application_returns_a_successful_response(
    )
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_checking_if_the_admin_login_api_with_empty_email_and_password_gives_validation_response(
    )
    {
        $response = $this->postJson(
            '/api/v1/admin/login'
        );
        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed to authenticate user',
                'errors'  => [
                    "email"    => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ],
                'trace'   => []
            ]
        )->assertStatus(422);
    }

    public function test_testing_the_admin_login_api_with_wrong_email_format_gives_validation_response(
    )
    {
        $response = $this->postJson(
            '/api/v1/admin/login',
            ['email' => 'abcd', 'password' => 'admin']
        );
        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed to authenticate user',
                'errors'  => [
                    "email" => [
                        "The email must be a valid email address."
                    ]
                ],
                'trace'   => []
            ]
        )->assertStatus(422);
    }

    public function test_checking_if_the_admin_login_api_with_wrong_credentials_throws_validation_response(
    )
    {
        $response = $this->postJson(
            '/api/v1/admin/login',
            ['email' => 'abcd@gmail.com', 'password' => 'abcd']
        );
        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed to authenticate user',
                'errors'  => [],
                'trace'   => []
            ]
        )->assertStatus(422);
    }

    public function test_testing_if_the_admin_login_is_successful_with_correct_credentials(
    )
    {
        $response = $this->postJson(
            '/api/v1/admin/login',
            [
                'email'    => 'admin@gmail.com',
                'password' => 'admin'
            ]
        );

        $response->assertJsonStructure(
            [
                'success',
                'data' => ['token'],
                'error',
                'errors',
                'extra'
            ]
        )->assertStatus(200);
    }

//    Admin logout API endpoint
    public function test_checking_if_the_admin_logout_gives_successful_response(
    )
    {
        $response = $this->get(
            '/api/v1/admin/logout'
        );

        $response->assertJson(
            [
                'success' => 1,
                'data'    => [],
                'error'   => null,
                'errors'  => [],
                'extra'   => []
            ]
        )->assertStatus(200);
    }

}
