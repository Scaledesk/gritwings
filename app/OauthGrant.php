<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthGrant extends Model {
	const ID = 'id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_grants';
    protected $fillable = ['id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthClients() {
        return $this->belongsToMany('App\OauthClient', 'oauth_client_grants', 'grant_id', 'client_id');
    }

    public function oauthScopes() {
        return $this->belongsToMany('App\OauthScope', 'oauth_grant_scopes', 'grant_id', 'scope_id');
    }

    public function oauthClientGrants() {
        return $this->hasMany('App\OauthClientGrant', 'grant_id', 'id');
    }

    public function oauthGrantScopes() {
        return $this->hasMany('App\OauthGrantScope', 'grant_id', 'id');
    }


}
