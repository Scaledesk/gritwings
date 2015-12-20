<?php namespace App\Api\Transformers;

use App\ParentService;
use League\Fractal\TransformerAbstract;

class ParentServiceTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(ParentService $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            
        ];
    }


}

