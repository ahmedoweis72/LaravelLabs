<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a few users if none exist
        if (User::count() === 0) {
            User::factory(5)->create();
            
            User::factory()->create([
                'name' => 'Ahmed Ramadan',
                'email' => 'ahmed@gmail.com',
            ]);
        }

        // Only call PostSeeder after ensuring we have users
        $this->call(PostSeeder::class);
    }
}
