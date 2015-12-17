<?php namespace App\Api\Transformers;

use App\Addon;
use League\Fractal\TransformerAbstract;

class AddonTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(Addon $item)
    {
        return [
				'id'  =>  $item->id,
            'title'  =>  $item->title,
            'terms_conditions'  =>  $item->terms_conditions,
            'amount'  =>  $item->amount,
            'package_id'  =>  $item->package_id,
            'description'  =>  $item->description,
            
        ];
    }


}

