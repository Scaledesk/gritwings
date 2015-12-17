<?php namespace App\Api\Transformers;

use App\AssignmentStatus;
use League\Fractal\TransformerAbstract;

class AssignmentStatusTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(AssignmentStatus $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            
        ];
    }


}

