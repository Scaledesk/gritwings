<?php

use Illuminate\Database\Seeder;

class DeliveryTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\DeliveryType')->times(5)->create();
    }
}
