<?php namespace App\Api\Transformers;

use App\Bid;
use League\Fractal\TransformerAbstract;

class BidTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(Bid $item)
    {
        return [
				'id'  =>  $item->id,
            'amount'  =>  $item->amount,
            'delivery_date'  =>  $item->delivery_date,
            'comments'  =>  $item->comments,
            'user_id'  =>  $item->user_id,
            'assignment_id'  =>  $item->assignment_id,
            
        ];
    }


}

