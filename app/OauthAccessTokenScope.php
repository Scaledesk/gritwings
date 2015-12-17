<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAccessTokenScope extends Model {
	const ID = 'id';
        const ACCESS_TOKEN_ID = 'access_token_id';
        const SCOPE_ID = 'scope_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_access_token_scopes';
    protected $fillable = ['id', 'access_token_id', 'scope_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthAccessToken() {
        return $this->belongsTo('App\OauthAccessToken', 'access_token_id', 'id');
    }

    public function oauthScope() {
        return $this->belongsTo('App\OauthScope', 'scope_id', 'id');
    }


}
