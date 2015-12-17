<?php namespace App\Api\Transformers;

use App\ChildService;
use App\ParentService;
use League\Fractal\TransformerAbstract;

class ChildServiceTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     *
     * @return array
     */
    protected $defaultIncludes = [
        'parent_service'
    ];

    public function transform(ChildService $item)
    {
        return [
            'id'                => $item->id,
            'name'              => $item->name,
            'parent_service_id' => $item->parent_service_id,

        ];
    }

    public function includeParentService(ChildService $childService)
    {
        if($childService->parentService)
        {
            return $this->item($childService->parentService
                , new ParentServiceTransformer());
        }
        else{
            return null;
        }
    }
}

