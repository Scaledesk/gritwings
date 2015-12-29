<?php namespace App\Api\Transformers;

use App\Package;
use League\Fractal\TransformerAbstract;

class PackageTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     *
     * @return array
     */
    protected $defaultIncludes = [
//        'addons',
//        'user',
//        'category',
//        'package_type',
//        'payment_types',
//        'delivery_types',
//        'tags',
//        'reviews',
//        'package_status'
    ];

    public function transform(Package $item)
    {
        return [
            'id'                    => $item->id,
//            'title'                 => $item->title,
//            'slug'                  => $item->slug,
//            'description'           => $item->description,
            'category_id'           => $item->category_id,
            'price'                 => $item->price,
            'deal_price'            => $item->deal_price,
//            'terms_conditions'      => $item->terms_conditions,
//            'delivery_time'         => $item->delivery_time,
//            'user_id'               => $item->user_id,
//            'status_id'                => $item->status_id,
//            'instructions_to_buyer' => $item->instructions_to_buyer,
//            'location'              => $item->location,
//            'meeting_availability'  => $item->meeting_availability,
//            'meeting_address'       => $item->meeting_address,
//            'created_at'            => $item->created_at,
//            'updated_at'            => $item->updated_at,
            'package_type_id'       => $item->package_type_id,
            'average_rating'        => $this->getAverageRating($item)

        ];
    }

    public function getAverageRating($item)
    {
        $average_rating = 0;
        $reviews        = $item->reviews()
                               ->get()
                               ->all();
        if(count($reviews) == 0)
        {
            return 0;
        }
        foreach ($reviews as $review) {

            $average_rating += ($review->seller_as_described_rating + $review->seller_would_recommend_rating + $review->seller_communication_rating);
        }
        $average_rating = $average_rating / (count($reviews) * 3);
        return $average_rating;
    }

    public function includeAddons(Package $package)
    {
        return $this->collection($package->addons()
                                         ->get(), new AddonTransformer());
    }

    public function includeUser(Package $package)
    {
        if($package->user)
        {
            return $this->item($package->user
                , new UserTransformer());
        }
        else{
            return null;
        }

    }

    public function includeCategory(Package $package)
    {
        if($package->category)
        {
            return $this->item($package->category, new CategoryTransformer());
        }
        else{
            return null;
        }
    }

    public function includePackageType(Package $package)
    {

        if($package->packageType)
        return $this->item($package->packageType, new PackageTypeTransformer());
        else
        return null;
    }

    public function includeDeliveryTypes(Package $package)
    {
        return $this->collection($package->deliveryTypes()
                                         ->get(), new DeliveryTypeTransformer());
    }

    public function includePaymentTypes(Package $package)
    {
        return $this->collection($package->paymentTypes()
                                         ->get(), new PaymentTypeTransformer());
    }

    public function includeTags(Package $package)
    {
        return $this->collection($package->tags()
                                         ->get(), new TagTransformer());
    }


    public function includeReviews(Package $package)
    {
        return $this->collection($package->reviews()
                                         ->get(), new ReviewTransformer());
    }
    public function includePackageStatus(Package $package)
    {
        if($package->packageStatus)
        return $this->item($package->packageStatus, new PackageStatusTransformer());
        else
            return null;
    }
}

