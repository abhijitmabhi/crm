<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LocalheroPortal\Models\User\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'avatar' => null,
            'password' => $this->faker->password,
            'api_token' => null,
            'remeber_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'options' => null,
            'is_active' => 1
        ];
    }
}
