<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model {
	const ID = 'id';
        const AMOUNT = 'amount';
        const DELIVERY_DATE = 'delivery_date';
        const COMMENTS = 'comments';
        const USER_ID = 'user_id';
        const ASSIGNMENT_ID = 'assignment_id';
        
    protected $table = 'bids';
    protected $fillable = ['id', 'amount', 'delivery_date', 'comments', 'user_id', 'assignment_id'];
	public $timestamps = true;

    public function assignment() {
        return $this->belongsTo('App\Assignment', 'assignment_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function assignments() {
        return $this->hasMany('App\Assignment', 'bid_id', 'id');
    }


}
