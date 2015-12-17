<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthSession extends Model {
	const ID = 'id';
        const CLIENT_ID = 'client_id';
        const OWNER_TYPE = 'owner_type';
        const OWNER_ID = 'owner_id';
        const CLIENT_REDIRECT_URI = 'client_redirect_uri';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_sessions';
    protected $fillable = ['id', 'client_id', 'owner_type', 'owner_id', 'client_redirect_uri', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthClient() {
        return $this->belongsTo('App\OauthClient', 'client_id', 'id');
    }

    public function oauthScopes() {
        return $this->belongsToMany('App\OauthScope', 'oauth_session_scopes', 'session_id', 'scope_id');
    }

    public function oauthAccessTokens() {
        return $this->hasMany('App\OauthAccessToken', 'session_id', 'id');
    }

    public function oauthAuthCodes() {
        return $this->hasMany('App\OauthAuthCode', 'session_id', 'id');
    }

    public function oauthSessionScopes() {
        return $this->hasMany('App\OauthSessionScope', 'session_id', 'id');
    }


}
