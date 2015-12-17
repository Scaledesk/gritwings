<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentService extends Model {
	const ID = 'id';
        const NAME = 'name';
        
    protected $table = 'parent_services';
    protected $fillable = ['id', 'name'];
	public $timestamps = false;

    public function childServices() {
        return $this->hasMany('App\ChildService', 'parent_service_id', 'id');
    }


}
