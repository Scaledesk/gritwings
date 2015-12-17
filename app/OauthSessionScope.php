<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthSessionScope extends Model {
	const ID = 'id';
        const SESSION_ID = 'session_id';
        const SCOPE_ID = 'scope_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_session_scopes';
    protected $fillable = ['id', 'session_id', 'scope_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthScope() {
        return $this->belongsTo('App\OauthScope', 'scope_id', 'id');
    }

    public function oauthSession() {
        return $this->belongsTo('App\OauthSession', 'session_id', 'id');
    }


}
