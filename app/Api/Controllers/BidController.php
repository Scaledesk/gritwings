<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Bid;
use App\Api\Transformers\BidTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Illuminate\Support\Facades\Validator;
class BidController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Bid;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new BidTransformer;
    }

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('oauth', ['except' => ['index']]);

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

}
