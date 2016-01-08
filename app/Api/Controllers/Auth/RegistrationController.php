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
                    $user_extra=$this->insertExtra($user->id);

                        //to be implemented
                        // mail has to be sent to the admin all details of the newly signed up expert.
                        $data=[
                            "first_name"=>isset($user_extra->first_name)&&($user_extra->first_name)!=NULL?$user_extra->first_name:"not available",
                            "last_name"=>isset($user_extra->last_name)&&($user_extra->last_name)!=NULL?$user_extra->last_name:"not available",
                            "personal_email"=>isset($user_extra->personal_email)&&($user_extra->personal_email)!=NULL?$user_extra->personal_email:"not available",
                            "highest_degree"=>isset($user_extra->highest_degree)&&($user_extra->highest_degree)!=NULL?$user_extra->highest_degree:"not available",
                            "major_specialization"=>isset($user_extra->major_specialization)&&($user_extra->major_specialization)!=NULL?$user_extra->major_specialization:"not available",
                            "profession"=>isset($user_extra->profession)&&($user_extra->profession)!=NULL?$user_extra->profession:"not available",
                            "expert_area"=>isset($user_extra->expert_area)&&($user_extra->expert_area)!=NULL?$user_extra->expert_area:"not available",
                            "linkedin_url"=>isset($user_extra->linkedin_url)&&($user_extra->linkedin_url)!=NULL?$user_extra->linkedin_url:"not available",
                            "research_gate_id"=>isset($user_extra->research_gate_id)&&($user_extra->research_gate_id)!=NULL?$user_extra->research_gate_id:"not available",
                            "facebook_url"=>isset($user_extra->facebook_url)&&($user_extra->facebook_url)!=NULL?$user_extra->facebook_url:"not available",
                            "resume_url"=>isset($user_extra->resume_url)&&($user_extra->resume_url)!=NULL?$user_extra->resume_url:"not available",
                            "email"=>isset($user->email)&&($user->email)!=NULL?$user->email:"not available",
                            "mobile_number"=>isset($user->mobile_number)&&($user->mobile_number)!=NULL?$user->mobile_number:"not available",
                            "description"=>isset($user->description)&&($user->description)!=NULL?$user->description:"not available",
                            "gender"=>isset($user->gender)&&($user->gender)!=NULL?$user->gender:"not available",
                            "birth_date"=>isset($user->birth_date)&&($user->birth_date)!=NULL?$user->birth_date:"not available",
                            "image"=>isset($user->image)&&($user->image)!=NULL?$user->image:"not available",
                        ];
                        Mail::send('email.email_to_admin', $data, function ($message) {
                            $message->to("tamyworld@gmail.com", "Admin")
                                ->subject('New Expert Registration');
                        });

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
        $user_extra=Userextra::create($data);
        return $user_extra;
    }
}
