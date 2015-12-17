<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClientEndpoint extends Model {
	const ID = 'id';
        const CLIENT_ID = 'client_id';
        const REDIRECT_URI = 'redirect_uri';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'oauth_client_endpoints';
    protected $fillable = ['id', 'client_id', 'redirect_uri', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function oauthClient() {
        return $this->belongsTo('App\OauthClient', 'client_id', 'id');
    }


}
