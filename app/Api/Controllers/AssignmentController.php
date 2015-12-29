<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Assignment;
use App\Api\Transformers\AssignmentTransformer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class AssignmentController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Assignment;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new AssignmentTransformer;
    }

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('oauth');

        $this->model       = $this->model();
        $this->transformer = $this->transformer();

        $this->fractal = new Manager();
        $this->fractal->setSerializer($this->serializer());

        $this->request = $request;

        if ($this->request->has('include')) {
            $this->fractal->parseIncludes(camel_case($this->request->input('include')));
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/{resource}.
     *
     * @return Response
     */
    public function store()
    {

        $data = $this->request->json()
                              ->get($this->resourceKeySingular);

        if (!$data) {
            return $this->errorWrongArgs('Empty data');
        }
        $data['user_id'] = Authorizer::getResourceOwnerId();
        $validator       = Validator::make($data, $this->rulesForCreate());
        if ($validator->fails()) {
            return $this->errorWrongArgs($validator->messages());
        }

        $this->unguardIfNeeded();

        $item = $this->model->create($data);

        return $this->respondWithItem($item);
    }

    public function getUserAssignmentsByStatus($statusId)
    {
        $userId = Authorizer::getResourceOwnerId();


        $items = $this->model->where('user_id',$userId)->where('status_id',$statusId)->get();
        return $this->respondWithCollection($items);
    }

    public function getAllAssignmentsByStatus($statusId)
    {

        $with = $this->getEagerLoad();

        $items = $this->model->where('status_id',$statusId)->get();
        return $this->respondWithCollection($items);
    }

    public function getExpertUndergoingAssignments(){
        $userId = Authorizer::getResourceOwnerId();
        $status = [2,3,4,5,6];

        $items = $this->model->whereIn('status_id',$status)->whereHas('bid', function ($query) {
            $query->where('user_id', Authorizer::getResourceOwnerId());
        })->get();
        return $this->respondWithCollection($items);
    }

    public function getExpertAssignmentsByStatus($statusId){
        $userId = Authorizer::getResourceOwnerId();

        $items = $this->model->where('status_id',$statusId)->whereHas('bid', function ($query) {
            $query->where('user_id', Authorizer::getResourceOwnerId());
        })->get();
        return $this->respondWithCollection($items);
    }
}
