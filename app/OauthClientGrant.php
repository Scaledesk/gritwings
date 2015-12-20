<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClientGrant extends Model {
	const ID = 'id';
        const CLIENT_ID = 'client_id';
        const GRANT_ID = 'grant_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_client_grants';
    protected $fillable = ['id', 'client_id', 'grant_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthClient() {
        return $this->belongsTo('App\OauthClient', 'client_id', 'id');
    }

    public function oauthGrant() {
        return $this->belongsTo('App\OauthGrant', 'grant_id', 'id');
    }


}
