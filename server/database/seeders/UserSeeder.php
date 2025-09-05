<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@test.co',
            'password' => Hash::make('12341234'),
            'avatar_url' => 'https://i.pravatar.cc/300?u=' . fake()->randomNumber(2),
        ]);
        User::factory(4)->create();
    }
}
