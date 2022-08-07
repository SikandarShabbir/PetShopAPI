<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'uuid'              => fake()->uuid(),
                'first_name'        => 'Admin',
                'last_name'         => 'admin',
                'is_admin'          => 1,
                'email'             => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password'          => 'admin',
                'avatar'            => fake()->uuid(),
                'address'           => fake()->address(),
                'phone_number'      => fake()->phoneNumber(),
                'is_marketing'      => fake()->numberBetween(0, 1)
            ]
        );
        User::create(
            [
                'uuid'              => fake()->uuid(),
                'first_name'        => 'User',
                'last_name'         => 'user',
                'is_admin'          => 0,
                'email'             => 'user@gmail.com',
                'email_verified_at' => now(),
                'password'          => 'user',
                'avatar'            => fake()->uuid(),
                'address'           => fake()->address(),
                'phone_number'      => fake()->phoneNumber(),
                'is_marketing'      => fake()->numberBetween(0, 1)
            ]
        );
        
        User::factory(10)->create();
    }
}
