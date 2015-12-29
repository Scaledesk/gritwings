<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('oauth_clients')
          ->truncate();
        DB::table('roles')
          ->truncate();
        DB::table('oauth_scopes')
          ->truncate();
        DB::table('oauth_client_grants')
          ->truncate();
        DB::table('oauth_grants')
          ->truncate();
        DB::table('oauth_client_scopes')
          ->truncate();
        DB::table('oauth_access_tokens')
          ->truncate();
        DB::table('oauth_access_token_scopes')
          ->truncate();
        DB::table('oauth_sessions')
          ->truncate();
        DB::table('oauth_session_scopes')
          ->truncate();
        DB::table('oauth_grant_scopes')
          ->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call(OauthClientsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(OauthScopesTableSeeder::class);
        $this->call(OauthGrantsTableSeeder::class);
        $this->call(OauthClientGrantsTableSeeder::class);
        $this->call(OauthClientScopesTableSeeder::class);
        $this->call(OauthGrantScopesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
}
