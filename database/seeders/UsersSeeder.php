<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create a new tenant
        $tenant = new Tenant();
        $tenant->id = 1;
        $tenant->name = 'Coco and Jay';
        $tenant->save();

        $user = new User();
        $user->name = 'Emanuele Nicolella';
        $user->email = 'em@cocoandjay.com';
        $user->password = Hash::make('12Pineapple!');
        $user->email_verified_at = now();
        $user->save();

        $user2 = new User();
        $user2->name = 'Georg Schufft';
        $user2->email = 'georg@cocoandjay.com';
        $user2->password = Hash::make('r');
        $user2->email_verified_at = now();
        $user2->save();

        $tenant->users()->attach([$user1->id, $user2->id]);
    }
}