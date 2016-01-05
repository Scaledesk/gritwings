<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\ChildService;
use App\Role;
use App\User;
use App\Api\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
    }
    public function getNewExperts(){
        $role=Role::where('name','Expert')->select(['id'])->first();
        $experts=null;
        if(!is_null($role)){
            $experts=$role->users()->select(['user_id','name','email'])->get();
        }
        if(is_null($experts)){
            $this->setStatusCode(404);
            return $this->respondWithArray([
                'experts'=>[],
                'status_code'=>404
            ]);
        }else{
            $transformed_data=[];
            foreach($experts as $expert){
                $data=[
                    "id"=>$expert['user_id'],
                    "name"=>$expert['name'],
                    "email"=>$expert['email']
                ];
                array_push($transformed_data,$data);
                unset($data);
            }
            unset($experts,$expert);
        }
        $this->setStatusCode(200);
          return $this->respondWithArray([
              'experts'=>$transformed_data,
              'status_code'=>200
          ]);
    }
    public function activateAccount($id){
        $user=User::where('id',$id)->first();
        if(is_null($user)){
            $this->setStatusCode(404);
            return $this->respondWithArray([
                'message'=>'User not found',
                'status_code'=>404
            ]);
        }else{
            $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                .'0123456789!@#$%^&*()'); // and any other characters
            shuffle($seed); // probably optional since array_is randomized; this may be redundant
            $rand = '';
            foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
            /*print_r($rand);
            die;*/
            $password=$rand; //password to be mailed
            $password_to_store=Hash::make($rand);  //password to be stored
            unset($rand);
            $user->update([
                'password'=>$password_to_store,
                'confirmed'=>1,
                'confirmation_code'=>NULL
            ]);
            $name=$user->name;
            $email=$user->email;
            if(is_null($user->userExtra)){
            $salutation=null;
            }else{
                $salutation=$user->userExtra->salutation;
            }
            $data=[
                'name'=>$name,
                'salutation'=>$salutation,
                'password'=>$password
            ];
            Mail::send('email.activateUser',$data, function($message)use($user,$email) {
                $message->to($email, $user->name)
                    ->subject('Account Activated');
            });
            $this->setStatusCode(200);
            return $this->respondWithArray([
                'message'=>'success',
                'status_code'=>200
            ]);
        }
    }
}
