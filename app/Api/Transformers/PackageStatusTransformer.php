<?php namespace App\Api\Transformers;

use App\PackageStatus;
use League\Fractal\TransformerAbstract;

class PackageStatusTransformer extends TransformerAbstract
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

    public function transform(PackageStatus $item)
    {
        return [
            'id'   => $item->id,
            'name' => $item->name,

        ];
    }
}

