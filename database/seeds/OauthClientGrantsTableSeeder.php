<?php

use Illuminate\Database\Seeder;

class OauthClientGrantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_client_grants')->insert([
            'client_id' => 'client_user',
            'grant_id' => 'password',
        ]);
        DB::table('oauth_client_grants')->insert([
            'client_id' => 'client_admin',
            'grant_id' => 'password',
        ]);
    }
}
