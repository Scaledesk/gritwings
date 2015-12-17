<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    const ID               = 'id';
    const TITLE            = 'title';
    const DESCRIPTION      = 'description';
    const DELIVERY_DATE    = 'delivery_date';
    const CHILD_SERVICE_ID = 'child_service_id';
    const FILE_URL         = 'file_url';
    const STATUS_ID        = 'status_id';
    const USER_ID          = 'user_id';
    const STATUS_COMMENT   = 'status_comment';
    const BID_ID           = 'bid_id';
    const EXPERT_FILE_URL  = 'expert_file_url';
    const USER_FILE_URL    = 'user_file_url';
    const EXPERT_COMMENTS  = 'expert_comments';
    const PAYMENT_STATUS   = 'payment_status';
    const FAILED_REASON = 'failed_reason';
    protected $table      = 'assignments';
    protected $fillable   = [
        'id',
        'title',
        'description',
        'delivery_date',
        'child_service_id',
        'file_url',
        'status_id',
        'user_id',
        'status_comment',
        'bid_id',
        'expert_file_url',
        'user_file_url',
        'expert_comments',
        'payment_status',
        'failed_reason'
    ];
    public    $timestamps = false;

    public function childService()
    {
        return $this->belongsTo('App\ChildService', 'child_service_id', 'id');
    }

    public function assignmentStatus()
    {
        return $this->belongsTo('App\AssignmentStatus', 'status_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function bid()
    {
        return $this->belongsTo('App\Bid', 'bid_id', 'id');
    }

    public function bids()
    {
        return $this->hasMany('App\Bid', 'assignment_id', 'id');
    }
}
