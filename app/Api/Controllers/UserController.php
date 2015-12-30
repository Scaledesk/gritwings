<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\ChildService;
use App\User;
use App\Api\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use Illuminate\Support\Facades\Input;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
class UserController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new User;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new UserTransformer;
    }

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

    public function myProfile(){
        $item = $this->model()->findOrFail(Authorizer::getResourceOwnerId());
        return $this->respondWithItem($item);
    }


    public function getExpertsOfService($serviceId){
        $users = $this->model()->whereHas('childServices', function ($query) use($serviceId) {
            $query->where('id', $serviceId);
        })->get();

        return $this->respondWithCollection($users);

    }
    public function updateProfile(){
        $data = $this->request->json()->get($this->resourceKeySingular);
        if (!$data) {
            return $this->errorWrongArgs('Empty data');
        }

        $user = User::findOrFail(Authorizer::getResourceOwnerId());

        if (!$user) {
            return $this->errorNotFound();
        }
        $services= $data['child_services'];
                        $user->childServices()->sync($services);
//             return $this->success();
//        $item = $this->model()->findOrFail(Authorizer::getResourceOwnerId());
//        if($this->request->has('child_services')){
//            die();
//        }
//        return $this->respondWithItem($item);
    }
}
