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

    //    Admin Create Account API Endpoint
    public function test_Checking_if_the_admin_create_user_api_validations_working_fine(
    )
    {
        $response = $this->post(
            '/api/v1/admin/create'
        );

        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed Validation',
                'errors'  => [
                    'first_name'            => [
                        'The first name field is required.'
                    ],
                    'last_name'             => [
                        'The last name field is required.'
                    ],
                    'email'                 => [
                        'The email field is required.'
                    ],
                    'password'              => [
                        'The password field is required.'
                    ],
                    'password_confirmation' => [
                        'The password confirmation field is required.'
                    ],
                    'avatar'                => [
                        'The avatar field is required.'
                    ],
                    'address'               => [
                        'The address field is required.'
                    ],
                    'phone_number'          => [
                        'The phone number field is required.'
                    ]
                ],
                'trace'   => []
            ]
        )->assertStatus(422);
    }

    public function test_generating_invalid_email_and_mismatch_token_validation_error_on_admin_create_api_endpoint(
    )
    {
        $response = $this->post(
            '/api/v1/admin/create',
            [
                'email'                 => 'abcd',
                'password'              => 'password',
                'password_confirmation' => 'passwor'
            ]
        );

        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed Validation',
                'errors'  => [
                    'email'                 => [
                        'The email must be a valid email address.'
                    ],
                    'password_confirmation' => [
                        'The password confirmation and password must match.'
                    ]
                ],
                'trace'   => []
            ]
        )->assertStatus(422);
    }

    public function test_creating_a_Admin_account_with_all_information_provided(
    )
    {
        $response = $this->post(
            '/api/v1/admin/create',
            [
                'first_name'            => 'updated first name',
                'last_name'             => 'updated last name',
                'email'                 => fake()->unique()->safeEmail(),
                'password'              => 'password',
                'password_confirmation' => 'password',
                'avatar'                => fake()->uuid(),
                'address'               => fake()->address(),
                'phone_number'          => fake()->phoneNumber(),
                'marketing'             => 'marketing',
            ]
        );

        $response->assertJson(
            [
                'success' => 1,
                'data'    => [],
                'error'   => '',
                'errors'  => [],
                'extra'   => []
            ]
        )->assertStatus(200);
    }

    //    Get User details

    public function test_getting_user_accounts_listing_without_authorization_is_unsuccessful(
    )
    {
        $response = $this->get(
            '/api/v1/admin/user-listing'
        );

        $response->assertJson(['error' => 'Unauthorized'])
            ->assertStatus(401);
    }

    public function test_listing_user_accounts_as_authorized_Admin_user()
    {
        $response = $this->post(
            '/api/v1/admin/login',
            [
                'email'    => 'admin@gmail.com',
                'password' => 'admin'
            ]
        );

        $token = $response->getOriginalContent()['data']['token'];
        $response = $this->getJson(
            '/api/v1/admin/user-listing?limit=1',
            [
                'Authorization' => 'Bearer '.$token,
                'Accept'        => 'application/json'
            ]
        );
        $response->assertJsonStructure(
            [
                'current_page',
                'data' => [
                    '*' => [
                        'uuid',
                        'first_name',
                        'last_name',
                        'email',
                        'email_verified_at',
                        'avatar',
                        'address',
                        'phone_number',
                        'is_marketing',
                        'created_at',
                        'updated_at',
                        'last_login_at',
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]
        )->assertStatus(
            200
        );
    }

    //    Admin Update Endpoint
    public function test_Checking_admin_update_endpoint_as_unauthorized_user()
    {
        $response = $this->put(
            '/api/v1/admin/user-edit/{uuid}'
        );

        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Unauthorized',
                'errors'  => [],
                'trace'   => []
            ]
        )->assertStatus(
            401
        );
    }

    public function test_checking_validations_as_authorized_user_for_admin_account_update_endpoint(
    )
    {
        $response = $this->post(
            '/api/v1/admin/login',
            [
                'email'    => 'admin@gmail.com',
                'password' => 'admin'
            ]
        );

        $token = $response->getOriginalContent()['data']['token'];

        $response = $this->putJson(
            '/api/v1/admin/user-edit/{uuid}',
            [],
            [
                'Authorization' => 'Bearer '.$token,
                'Accept'        => 'application/json'
            ]
        );
        $response->assertJson(
            [
                'success' => 0,
                'data'    => [],
                'error'   => 'Failed Validation',
                'errors'  => [
                    'first_name'            => [
                        'The first name field is required.'
                    ],
                    'last_name'             => [
                        'The last name field is required.'
                    ],
                    'email'                 => [
                        'The email field is required.'
                    ],
                    'password'              => [
                        'The password field is required.'
                    ],
                    'password_confirmation' => [
                        'The password confirmation field is required.'
                    ],
                    'address'               => [
                        'The address field is required.'
                    ],
                    'phone_number'          => [
                        'The phone number field is required.'
                    ]
                ],
                'trace'   => []

            ]
        )->assertStatus(
            422
        );
    }

    public function test_trying_to_update_user_account_as_authorized_admin()
    {
        $uuid = User::where('is_admin', 0)->value('uuid');
        $response = $this->post(
            '/api/v1/admin/login',
            [
                'email'    => 'admin@gmail.com',
                'password' => 'admin'
            ]
        );

        $token = $response->getOriginalContent()['data']['token'];

        $response = $this->putJson(
            '/api/v1/admin/user-edit/'.$uuid,
            [
                'first_name'            => 'ABCD',
                'last_name'             => 'ABCD',
                'email'                 => 'user@gmail.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
                'avatar'                => 'ABCD',
                'address'               => 'ABCD',
                'phone_number'          => '0987654321',
                'marketing'             => 'marketing',
            ],
            [
                'Authorization' => 'Bearer '.$token,
                'Accept'        => 'application/json'
            ]
        );

        $response->assertJsonStructure(
            [
                'success',
                'data' => [
                    '*' => [
                        'uuid',
                        'first_name',
                        'last_name',
                        'email',
                        'email_verified_at',
                        'address',
                        'phone_number',
                        'is_marketing',
                        'created_at',
                        'updated_at',
                        'last_login_at',
                    ]
                ],
                'error',
                'errors',
                'extra',
            ]
        )->assertStatus(200);
    }
    
}
