<?php namespace App\Api\Transformers;

use App\Assignment;
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
        'bids'
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
            'completion_amount' => $item->completion_amount

        ];
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

