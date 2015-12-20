<?php

namespace App\Api\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        $enabled_registrations = [2];

        $confirmation_code = str_random(30);


        $data = ['name'              => Input::get('name'),
                 'email'             => Input::get('email'),
                 'password'          => Input::get('password'),
                 'role_id'           => Input::get('role_id'),
                 'confirmation_code' => $confirmation_code
        ];
        if (!in_array($data['role_id'], $enabled_registrations)) {
            return "Invalid role";
        }
        if ($this->validator($data)) {
            $user = $this->create($data);
            $user->roles()
                 ->attach($data['role_id']);

            $this->dispatch(new SendRegistrationEmail($user));
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
}
