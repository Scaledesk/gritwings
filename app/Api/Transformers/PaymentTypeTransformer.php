<?php namespace App\Api\Transformers;

use App\PaymentType;
use League\Fractal\TransformerAbstract;

class PaymentTypeTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(PaymentType $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            
        ];
    }


}

