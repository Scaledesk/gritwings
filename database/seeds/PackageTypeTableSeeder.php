<?php

use Illuminate\Database\Seeder;

class PackageTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\PackageType')->times(5)->create();
    }
}
