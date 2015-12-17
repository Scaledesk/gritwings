<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthGrantScope extends Model {
	const ID = 'id';
        const GRANT_ID = 'grant_id';
        const SCOPE_ID = 'scope_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_grant_scopes';
    protected $fillable = ['id', 'grant_id', 'scope_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthGrant() {
        return $this->belongsTo('App\OauthGrant', 'grant_id', 'id');
    }

    public function oauthScope() {
        return $this->belongsTo('App\OauthScope', 'scope_id', 'id');
    }


}
