<?php

use Illuminate\Database\Seeder;

class OauthGrantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_grants')->insert([
            'id' => 'password',
        ]);
    }
}
