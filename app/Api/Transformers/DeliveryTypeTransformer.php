<?php namespace App\Api\Transformers;

use App\DeliveryType;
use League\Fractal\TransformerAbstract;

class DeliveryTypeTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(DeliveryType $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            
        ];
    }


}

