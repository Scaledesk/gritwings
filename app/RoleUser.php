<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {
	const ID = 'id';
        const ROLE_ID = 'role_id';
        const USER_ID = 'user_id';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'role_user';
    protected $fillable = ['id', 'role_id', 'user_id', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function role() {
        return $this->belongsTo('App\Role', 'role_id', 'id');
    }


}
