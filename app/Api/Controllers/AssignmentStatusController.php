<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\AssignmentStatus;
use App\Api\Transformers\AssignmentStatusTransformer;

class AssignmentStatusController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new AssignmentStatus;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new AssignmentStatusTransformer;
    }
}
