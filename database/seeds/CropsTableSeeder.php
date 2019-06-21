<?php

use Illuminate\Database\Seeder;

class CropsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('crops')->insert(['name' => 'Wheat']);
        DB::table('crops')->insert(['name' => 'Broccoli']);
        DB::table('crops')->insert(['name' => 'Strawberry']);
    }

}
