<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //This will create a data in database by hardcoding the information
    public function run(): void
    {
        //
        DB::table('users')->insert([
            //Admin
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => 'active',
            ],
            //Agent
            [
                'name' => 'Agent',
                'username' => 'agent',
                'email' => 'agent@agent.com',
                'password' => Hash::make('222'),
                'role' => 'agent',
                'status' => 'active',
            ],
            //User
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@user.com',
                'password' => Hash::make('333'),
                'role' => 'user',
                'status' => 'active',
            ],
        ]);
    }
}
