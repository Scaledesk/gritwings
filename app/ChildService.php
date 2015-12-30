<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildService extends Model {
	const ID = 'id';
        const NAME = 'name';
        const PARENT_SERVICE_ID = 'parent_service_id';
        
    protected $table = 'child_services';
    protected $fillable = ['id', 'name', 'parent_service_id'];
	public $timestamps = false;

    public function parentService() {
        return $this->belongsTo('App\ParentService', 'parent_service_id', 'id');
    }

    public function assignments() {
        return $this->hasMany('App\Assignment', 'child_service_id', 'id');
    }

    public function users(){
        return $this->belongsToMany('App\User','expert_services','child_services_id','expert_id');
    }
}
