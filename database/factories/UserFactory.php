<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //Definicion en el facotr, a laravel le tengo que decir que campos manejar en esta clase
            //Elk objeito de factoriues es crear instancias del modelo
            // una forma "name"=> $this->fake()->name,
            'name'=> fake()->name,
            'lastname'=> fake()->lastName,
            'username' => fake()->unique()->userName,
            'email'=> fake()->unique()->safeEmail,
            'password'=> fake()->password,


        ];
    }
}
