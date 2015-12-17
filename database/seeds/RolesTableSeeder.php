<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'Manage Administrative functions'
        ]);
        DB::table('roles')->insert([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'User of the application'
        ]);
    }
}
