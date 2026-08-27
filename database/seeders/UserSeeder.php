<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'superadmin@gmail.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = 'Super Admin';
            $user->email = 'superadmin@gmail.com';
            $user->password = Hash::make('12345');
            $user->save();
            $user->assignRole('superadmin');
        }
    }
}
