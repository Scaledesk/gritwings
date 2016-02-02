<?php namespace App\Api\Transformers;

use App\Assignment;
use App\User;
use Cmgmyr\Messenger\Models\Thread;
use League\Fractal\TransformerAbstract;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
class AssignmentTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     *
     * @return array
     */
    protected $defaultIncludes = [
        'status',
        'child_service',
        'bids',
        'bidders'
    ];

    public function transform(Assignment $item)
    {
        return [
            'id'                        => $item->id,
            'title'                     => $item->title,
            'description'               => $item->description,
            'delivery_date'             => $item->delivery_date,
            'child_service_id'          => $item->child_service_id,
            'file_url'                  => $item->file_url,
            'status_id'                 => $item->status_id,
            'user_id'                   => $item->user_id,
            'status_comment'            => $item->status_comment,
            'bid_id'                    => $item->bid_id,
            'user_bid_placed'           => $this->userBidPlaced($item),
            Assignment::EXPERT_COMMENTS => $item->expert_comments,
            Assignment::USER_FILE_URL   => $item->user_file_url,
            Assignment::EXPERT_FILE_URL => $item->expert_file_url,
            Assignment::PAYMENT_STATUS => $item->payment_status,
            Assignment::FAILED_REASON => $item->failed_reason,
            Assignment::URGENCY => $item->urgency,
            Assignment::EXPECTED_COST => $item->expected_cost,
            'commission' => $item->commission,
            'booking_amount' => $item->booking_amount,
            'completion_amount' => $item->completion_amount,
            'isUserThreadUnread' => $this->isUserThreadUnread($item),
            'isExpertThreadUnread' => $this->isExpertThreadUnread($item),
//            'isQueryThreadUnread' => $this->isQueryThreadUnread($item),
            'unreadQueryThreads' => $this->unreadQueryThreads($item)
        ];
    }


    public function isUserThreadUnread($assignment){
        if($thread = Thread::where('subject', 'user-'.$assignment->id)->first())
        {
            return Thread::where('subject', 'user-'.$assignment->id)->first()->isUnread(Authorizer::getResourceOwnerId());
        }
        return false;
    }
    public function isExpertThreadUnread($assignment){
        if($thread = Thread::where('subject', 'expert-'.$assignment->id)->first())
        {
            return Thread::where('subject', 'expert-'.$assignment->id)->first()->isUnread(Authorizer::getResourceOwnerId());
        }
        return false;
    }
    public function isQueryThreadUnread($assignment){
        $flag = false;
        if($threads = Thread::where('subject','LIKE', '%query-'.$assignment->id.'%')->get())
        {
            foreach($threads as $thread)
            {
                if($thread->isUnread(Authorizer::getResourceOwnerId()))
                {
                    $flag = true;
                }
            }
        }
        return $flag;
    }
    public function unreadQueryThreads($assignment){
        $unreadThreads = [];
        if($threads = Thread::where('subject','LIKE', '%query-'.$assignment->id.'%')->get())
        {
            foreach($threads as $thread)
            {
                if($thread->isUnread(Authorizer::getResourceOwnerId()))
                {
                    array_push($unreadThreads,$thread->subject);
                }
            }
        }
        return $unreadThreads;
    }
    public function isAdmin($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 1)
            {
                return 1;
            }
        }
        return 0;
    }
    public function isUser($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 2)
            {
                return 1;
            }
        }
        return 0;
    }
    public function isExpert($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 3)
            {
                return 1;
            }
        }
        return 0;
    }
    public function includeStatus(Assignment $assignment)
    {
        if ($assignment->assignmentStatus) {
            return $this->item($assignment->assignmentStatus
                , new AssignmentStatusTransformer());
        } else {
            return null;
        }
    }

    public function includeChildService(Assignment $assignment)
    {
        if ($assignment->childService) {
            return $this->item($assignment->childService
                , new ChildServiceTransformer());
        } else {
            return null;
        }
    }

    public function includeBids(Assignment $assignment)
    {
        return $this->collection($assignment->bids()
                                         ->get(), new BidTransformer());
    }
    public function includeBidders(Assignment $assignment)
    {
        return $this->collection($assignment->bidders()
            ->get(), new UserTransformer());
    }
    public function userBidPlaced(Assignment $assignment)
    {

        $bid = $assignment->bids()
                          ->where('user_id', Authorizer::getResourceOwnerId())
                          ->get()
                          ->count();
        if ($bid) {
            return 1;
        } else {
            return 0;
        }
    }
}

