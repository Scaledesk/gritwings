<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model {
	const ID = 'id';
        const SECRET = 'secret';
        const NAME = 'name';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_clients';
    protected $fillable = ['id', 'secret', 'name', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthGrants() {
        return $this->belongsToMany('App\OauthGrant', 'oauth_client_grants', 'client_id', 'grant_id');
    }

    public function oauthScopes() {
        return $this->belongsToMany('App\OauthScope', 'oauth_client_scopes', 'client_id', 'scope_id');
    }

    public function oauthClientEndpoints() {
        return $this->hasMany('App\OauthClientEndpoint', 'client_id', 'id');
    }

    public function oauthClientGrants() {
        return $this->hasMany('App\OauthClientGrant', 'client_id', 'id');
    }

    public function oauthClientScopes() {
        return $this->hasMany('App\OauthClientScope', 'client_id', 'id');
    }

    public function oauthSessions() {
        return $this->hasMany('App\OauthSession', 'client_id', 'id');
    }


}
