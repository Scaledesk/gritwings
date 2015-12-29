<?php namespace App\Api\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     *
     * @return array
     */
    protected $defaultIncludes = [

    ];

    public function transform(Category $item)
    {
        return [
            'id'        => $item->id,
            'name'      => $item->name,
            'slug'      => $item->slug,
            'parent_id' => $item->parent_id,

        ];
    }
}

