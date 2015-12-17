<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\ParentService;
use App\Api\Transformers\ParentServiceTransformer;

class ParentServiceController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new ParentService;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new ParentServiceTransformer;
    }
}
