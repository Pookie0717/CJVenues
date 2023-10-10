<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $demoUser = User::create([
            'name'              => 'Emanuele Nicolella',
            'email'             => 'em@cocoandjay.com',
            'password'          => Hash::make('12Pineapple!'),
            'email_verified_at' => now(),
        ]);
        $demoUser2 = User::create([
            'name'              => 'Georg Schufft',
            'email'             => 'georg@cocoandjay.com',
            'password'          => Hash::make('r'),
            'email_verified_at' => now(),
         ]);
    }
}
