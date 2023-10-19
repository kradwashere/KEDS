<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'email' => 'admin@keds.com',
            'password' => bcrypt('123456789'),
            'name' => 'Keds Monteplaya',
            'mobile' => '09123456789',
            'role' => 'Administrator',
            'avatar' => 'avatar.jpg',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ]);
        $this->call(DropdownsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
    }
}
