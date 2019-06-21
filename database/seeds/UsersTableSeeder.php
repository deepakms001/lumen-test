<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('users')->insert(['name' => 'Admin', 'email' => 'admin@test.com',
            'user_type' => 'Admin', 'password' => app('hash')->make('admin')]);
        DB::table('users')->insert(['name' => 'Supervisor', 'email' => 'supervisor@test.com',
            'user_type' => 'Supervisor', 'password' => app('hash')->make('supervisor')]);
        
        DB::table('users')->insert(['name' => 'Customer1', 'email' => 'customer1@test.com',
            'user_type' => 'Customer', 'password' => app('hash')->make('customer')]);
        DB::table('users')->insert(['name' => 'Customer2', 'email' => 'customer2@test.com',
            'user_type' => 'Customer', 'password' => app('hash')->make('customer')]);
        DB::table('users')->insert(['name' => 'Customer3', 'email' => 'customer3@test.com',
            'user_type' => 'Customer', 'password' => app('hash')->make('customer')]);
        DB::table('users')->insert(['name' => 'Customer4', 'email' => 'customer4@test.com',
            'user_type' => 'Customer', 'password' => app('hash')->make('customer')]);
        DB::table('users')->insert(['name' => 'Customer5', 'email' => 'customer5@test.com',
            'user_type' => 'Customer', 'password' => app('hash')->make('customer')]);
    }

}
