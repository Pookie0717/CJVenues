<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Season;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create a new tenant
        $tenant = new Tenant();
        $tenant->id = 1;
        $tenant->name = 'Coco and Jay';
        $tenant->save();

        // Create the users
        $user1 = new User();
        $user1->name = 'Emanuele Nicolella';
        $user1->email = 'em@cocoandjay.com';
        $user1->password = Hash::make('12Pineapple!');
        $user1->email_verified_at = now();
        $user1->save();

        $user2 = new User();
        $user2->name = 'Georg Schufft';
        $user2->email = 'georg@cocoandjay.com';
        $user2->password = Hash::make('r');
        $user2->email_verified_at = now();
        $user2->save();

        // Create a new season
        $season = new Season();
        $season->id = 0;
        $season->name = 'All';
        $season->date_from = '01-01-0000';
        $season->date_to = '31-12-9999';
        $season->priority = 0;
        $season->tenant_id = 1;
        $season->save();

        // Attach the users to the tenant
        $tenant->users()->attach([$user1->id, $user2->id]);
    }
}