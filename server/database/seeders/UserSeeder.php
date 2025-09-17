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
        // The primary user
        User::factory()->create([
            'name' => 'John Tess',
            'email' => 'test@test.co',
            'password' => Hash::make('12341234'),
        ]);

        User::factory(4)->create();
    }
}
