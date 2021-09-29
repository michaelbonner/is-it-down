<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Michael Bonner',
            'email' => 'mike@bootpackdigital.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
        ]);
    }
}
