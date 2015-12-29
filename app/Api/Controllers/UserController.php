<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\ChildService;
use App\User;
use App\Api\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Fractal\Manager;
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
        public function attachChildServiceToUser(){
            $service_ids=Input::get('services_ids','');
            $user = User::findOrFail(Authorizer::getResourceOwnerId());
                $user->services()->attach($service_ids);

            return $this->success();
        }
}
