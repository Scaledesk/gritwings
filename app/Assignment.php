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
    const EXPECTED_COST = 'expected_cost';
    const URGENCY = 'urgency';
    const BOOKING_AMOUNT = 'booking_amount';
    const COMMISSION = 'commission';
    const COMPLETION_AMOUNT='completion_amount';
    const TOTAL_AMOUNT='total_amount';
    const MINIMUN_BID='minimum_bid';
    const MAXIMUM_BID='maximum_bid';
    const LAST_BIDDING_DATE = 'last_bidding_date';

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
        'failed_reason',
        'commission',
        'booking_amount',
        'expected_cost',
        'urgency',
        'total_amount',
        'completion_amount',
        'minimum_bid',
        'maximum_bid',
        'last_bidding_date'
    ];
    public $timestamps = true;

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
    public function transactions(){
        return $this->hasMany('App\Assignment_Transaction');
    }
    public function bidders(){
        return  $this->belongsToMany('App\User','bidder_assignment', 'assignment_id', 'bidder_id');

    }
}
