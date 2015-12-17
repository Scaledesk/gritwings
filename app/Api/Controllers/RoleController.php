<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Role;
use App\Api\Transformers\RoleTransformer;

class RoleController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Role;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new RoleTransformer;
    }
}
