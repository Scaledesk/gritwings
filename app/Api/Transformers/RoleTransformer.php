<?php namespace App\Api\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(Role $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            'slug'  =>  $item->slug,
            'description'  =>  $item->description,
            'created_at'  =>  $item->created_at,
            'updated_at'  =>  $item->updated_at,
            
        ];
    }


}

