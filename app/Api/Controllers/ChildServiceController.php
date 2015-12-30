<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\ChildService;
use App\Api\Transformers\ChildServiceTransformer;

class ChildServiceController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new ChildService;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new ChildServiceTransformer;
    }

}
