<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'id' => 'client_user',
            'name' => 'client_user',
            'secret' => 'client_secret',
        ]);
        DB::table('oauth_clients')->insert([
            'id' => 'client_admin',
            'name' => 'client_admin',
            'secret' => 'client_secret',
        ]);
    }
}
