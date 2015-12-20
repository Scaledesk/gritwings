<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClientScope extends Model {
	const ID = 'id';
        const CLIENT_ID = 'client_id';
        const SCOPE_ID = 'scope_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_client_scopes';
    protected $fillable = ['id', 'client_id', 'scope_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthClient() {
        return $this->belongsTo('App\OauthClient', 'client_id', 'id');
    }

    public function oauthScope() {
        return $this->belongsTo('App\OauthScope', 'scope_id', 'id');
    }


}
