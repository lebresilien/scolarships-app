<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "matricule" => Student::generateUniqueMatricule(), 
            "fname" => fake()->firstName(), 
            "lname" => fake()->lastName(),
            //"sexe" => 1, 
            "father_name" => fake()->name(), 
            "mother_name" => fake()->name(),
            "fphone" => fake()->phoneNumber(), 
            "mphone" => fake()->phoneNumber(), 
            "born_at" => fake()->dateTime(),
            "born_place" => fake()->city(), 
            "allergy" => fake()->text(), 
            "description" => fake()->text(),
            "quarter" => fake()->streetAddress(),
        ];
    }
}
