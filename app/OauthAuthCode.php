<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAuthCode extends Model {
	const ID = 'id';
        const SESSION_ID = 'session_id';
        const REDIRECT_URI = 'redirect_uri';
        const EXPIRE_TIME = 'expire_time';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_auth_codes';
    protected $fillable = ['id', 'session_id', 'redirect_uri', 'expire_time', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthSession() {
        return $this->belongsTo('App\OauthSession', 'session_id', 'id');
    }

    public function oauthScopes() {
        return $this->belongsToMany('App\OauthScope', 'oauth_auth_code_scopes', 'auth_code_id', 'scope_id');
    }

    public function oauthAuthCodeScopes() {
        return $this->hasMany('App\OauthAuthCodeScope', 'auth_code_id', 'id');
    }


}
