<?php

use Illuminate\Database\Seeder;

class OauthGrantScopesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_grant_scopes')->insert([
            'grant_id' => 'password',
            'scope_id' => 'scope_user',
        ]);
        DB::table('oauth_grant_scopes')->insert([
            'grant_id' => 'password',
            'scope_id' => 'scope_admin',
        ]);
    }
}
