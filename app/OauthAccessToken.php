<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model {
	const ID = 'id';
        const SESSION_ID = 'session_id';
        const EXPIRE_TIME = 'expire_time';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_access_tokens';
    protected $fillable = ['id', 'session_id', 'expire_time', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthSession() {
        return $this->belongsTo('App\OauthSession', 'session_id', 'id');
    }

    public function oauthScopes() {
        return $this->belongsToMany('App\OauthScope', 'oauth_access_token_scopes', 'access_token_id', 'scope_id');
    }

    public function oauthAccessTokenScopes() {
        return $this->hasMany('App\OauthAccessTokenScope', 'access_token_id', 'id');
    }

    public function oauthRefreshToken() {
        return $this->hasOne('App\OauthRefreshToken', 'access_token_id', 'id');
    }


}
