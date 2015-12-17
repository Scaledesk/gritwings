<?php namespace App\Api\Transformers;

use App\PackageType;
use League\Fractal\TransformerAbstract;

class PackageTypeTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(PackageType $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            
        ];
    }


}

