<?php

use Illuminate\Database\Seeder;

class OauthScopesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_scopes')->insert([
            'id' => 'scope_user',
            'description' => 'User',
        ]);
        DB::table('oauth_scopes')->insert([
            'id' => 'scope_admin',
            'description' => 'Admin',
        ]);
    }
}
