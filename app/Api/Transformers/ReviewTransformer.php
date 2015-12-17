<?php namespace App\Api\Transformers;

use App\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     *
     * @return array
     */
    protected $defaultIncludes = [
        'user'
    ];

    public function transform(Review $item)
    {
        return [
            'id'                            => $item->id,
            'package_id'                    => $item->package_id,
            'review'                        => $item->review,
            'seller_as_described_rating'    => $item->seller_as_described_rating,
            'seller_communication_rating'   => $item->seller_communication_rating,
            'seller_would_recommend_rating' => $item->seller_would_recommend_rating,
            'user_id'                       => $item->user_id,
            'average_rating'                => ($item->seller_as_described_rating + $item->seller_communication_rating + $item->seller_would_recommend_rating) / 3

        ];
    }

    public function includeUser(Review $review)
    {
        return $this->item($review->user, new UserTransformer());
    }
}

