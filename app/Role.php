<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	const ID = 'id';
        const NAME = 'name';
        const SLUG = 'slug';
        const DESCRIPTION = 'description';
        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';
        
    protected $table = 'roles';
    protected $fillable = ['id', 'name', 'slug', 'description', 'created_at', 'updated_at'];
	public $timestamps = false;

    public function users() {
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
    }

    public function roleUsers() {
        return $this->hasMany('App\RoleUser', 'role_id', 'id');
    }


}
