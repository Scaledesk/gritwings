<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthScope extends Model {
	const ID = 'id';
        const DESCRIPTION = 'description';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_scopes';
    protected $fillable = ['id', 'description', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthAccessTokens() {
        return $this->belongsToMany('App\OauthAccessToken', 'oauth_access_token_scopes', 'scope_id', 'access_token_id');
    }

    public function oauthAuthCodes() {
        return $this->belongsToMany('App\OauthAuthCode', 'oauth_auth_code_scopes', 'scope_id', 'auth_code_id');
    }

    public function oauthClients() {
        return $this->belongsToMany('App\OauthClient', 'oauth_client_scopes', 'scope_id', 'client_id');
    }

    public function oauthGrants() {
        return $this->belongsToMany('App\OauthGrant', 'oauth_grant_scopes', 'scope_id', 'grant_id');
    }

    public function oauthSessions() {
        return $this->belongsToMany('App\OauthSession', 'oauth_session_scopes', 'scope_id', 'session_id');
    }

    public function oauthAccessTokenScopes() {
        return $this->hasMany('App\OauthAccessTokenScope', 'scope_id', 'id');
    }

    public function oauthAuthCodeScopes() {
        return $this->hasMany('App\OauthAuthCodeScope', 'scope_id', 'id');
    }

    public function oauthClientScopes() {
        return $this->hasMany('App\OauthClientScope', 'scope_id', 'id');
    }

    public function oauthGrantScopes() {
        return $this->hasMany('App\OauthGrantScope', 'scope_id', 'id');
    }

    public function oauthSessionScopes() {
        return $this->hasMany('App\OauthSessionScope', 'scope_id', 'id');
    }


}
