<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthRefreshToken extends Model {
	const ID = 'id';
        const ACCESS_TOKEN_ID = 'access_token_id';
        const EXPIRE_TIME = 'expire_time';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_refresh_tokens';
    protected $fillable = ['id', 'access_token_id', 'expire_time', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthAccessToken() {
        return $this->belongsTo('App\OauthAccessToken', 'access_token_id', 'id');
    }


}
