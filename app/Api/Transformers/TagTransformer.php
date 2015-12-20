<?php namespace App\Api\Transformers;

use App\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(Tag $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            'slug'  =>  $item->slug,
            
        ];
    }


}

