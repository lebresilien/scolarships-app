<?php

namespace Database\Seeders;

use App\Models\{ Student, User, Classroom }; 
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* User::factory(2)->state(new Sequence(
            ['remember_token' => 'YZZZEERTYUII102'],
            ['remember_token' => 'NDKKDJDJDOD8LD'],
        ))->create(); */

        $classroom = Classroom::find(17);

        Student::factory(5)->state(new Sequence(
            ['sexe' => 1],
            ['sexe' => 0],
        ))
        ->hasAttached(
            [$classroom],
            [
                'academic_id' => 1,
                'status' => true,
                'state' => true
            ]
        )->create();

       /*  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
