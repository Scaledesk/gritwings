<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAuthCodeScope extends Model {
	const ID = 'id';
        const AUTH_CODE_ID = 'auth_code_id';
        const SCOPE_ID = 'scope_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_auth_code_scopes';
    protected $fillable = ['id', 'auth_code_id', 'scope_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthAuthCode() {
        return $this->belongsTo('App\OauthAuthCode', 'auth_code_id', 'id');
    }

    public function oauthScope() {
        return $this->belongsTo('App\OauthScope', 'scope_id', 'id');
    }


}
