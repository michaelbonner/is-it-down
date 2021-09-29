<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
