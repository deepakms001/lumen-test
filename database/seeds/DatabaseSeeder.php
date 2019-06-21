<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();
        $this->call(CropsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        Model::reguard();
    }
}
