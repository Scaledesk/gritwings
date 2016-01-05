<?php

namespace App\Api\Controllers\Auth;

use App\Api\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use App\Userextra;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Role;
use App\RoleUser;
use App\Jobs\SendRegistrationEmail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendConfirmationEmail;

class RegistrationController extends Controller
{


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'confirmation_code' => $data['confirmation_code']
        ]);
    }

    public function register()
    {
        $enabled_registrations = [2,3];

        $confirmation_code = str_random(30);


        $data = ['name'              => Input::get('name'),
                 'email'             => Input::get('email'),
                 'password'          => Input::get('password'),
                 'role_id'           => Input::get('role_id'),
                 User::MOBILE_NUMBER           => Input::get('mobile_number',NULL),
                 User::DESCRIPTION           => Input::get('description',NULL),
                 User::IMAGE           => Input::get('image',NULL),
                 User::BIRTH_DATE           => Input::get('birth_date',NULL),
                 User::GENDER=> Input::get('gender',NULL),

                 'confirmation_code' => $confirmation_code
        ];
        if (!in_array($data['role_id'], $enabled_registrations)) {
            return "Invalid role";
        }
        if ($this->validator($data)) {
            $data=array_filter($data,'strlen');
            $user = User::create($data);
            $user->roles()
                 ->attach($data['role_id']);


            $role=Role::where('name','Expert')->select(['id'])->first();
            if(!is_null($role)){
                $role_id=$role->id;
                if($role_id==$data['role_id']){
                    $this->insertExtra($user->id);
                    function sendMailToAdmin(){
                        //to be implemented
                        // mail has to be sent to the admin all details of the newly signed up expert.
                    }
                    sendMailToAdmin();
                }else{
                    $this->dispatch(new SendRegistrationEmail($user));
                }
            }
            return "Registration Successfull";
        } else {
            return "Validation Error";
        }
    }

    public function confirm($confirmation_code)
    {
        if (!$confirmation_code) {
            return "error";
        }

        $user = User::whereConfirmationCode($confirmation_code)
                    ->first();

        if (!$user) {
            return "error";
        }

        $user->confirmed         = 1;
        $user->confirmation_code = null;
        $user->save();

        $this->dispatch(new SendConfirmationEmail($user));

        return "confirmed";
    }
    public function insertExtra($user_id=NULL){
        $data=call_user_func(array(new UserTransformer(),'userExtraTransformer'));
        $data=array_filter($data,'strlen');
        $data['user_id']=$user_id;
        Userextra::create($data);
    }
}
