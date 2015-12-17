<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentStatus extends Model {
	const ID = 'id';
        const NAME = 'name';
        
    protected $table = 'assignment_status';
    protected $fillable = ['id', 'name'];
	public $timestamps = false;

    public function assignments() {
        return $this->hasMany('App\Assignment', 'status_id', 'id');
    }


}
