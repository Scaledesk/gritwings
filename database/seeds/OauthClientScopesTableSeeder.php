<?php

use Illuminate\Database\Seeder;

class OauthClientScopesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_client_scopes')->insert([
            'client_id' => 'client_user',
            'scope_id' => 'scope_user',
        ]);
        DB::table('oauth_client_scopes')->insert([
            'client_id' => 'client_admin',
            'scope_id' => 'scope_admin',
        ]);
    }
}
