<?php namespace App\Api\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
//        'assignments',
//        'role_user',
        'roles',
         'services'
        ];

    public function transform(User $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            'email'  =>  $item->email,
            'password'  =>  $item->password,
            'confirmed'  =>  $item->confirmed,
            'confirmation_code'  =>  $item->confirmation_code,
            'remember_token'  =>  $item->remember_token,
            'created_at'  =>  $item->created_at,
            'updated_at'  =>  $item->updated_at,
            'mobile_number'  =>  $item->mobile_number,
            'description'  =>  $item->description,
            'image'  =>  $item->image,
            'birth_date'  =>  $item->birth_date,
            'gender'  =>  $item->gender,
            'google_id'  =>  $item->google_id,
            'social_auth_provider'  =>  $item->social_auth_provider,
            'social_auth_provider_id'  =>  $item->social_auth_provider_id,
            'social_auth_provider_access_token'  =>  $item->social_auth_provider_access_token,
            'facebook_id'  =>  $item->facebook_id,
             'is_admin' => $this->isAdmin($item),
             'is_user' => $this->isUser($item),
             'is_expert' => $this->isExpert($item)

        ];
    }
    public function isAdmin($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 1)
            {
                return 1;
            }
        }
        return 0;
    }
    public function isUser($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 2)
            {
                return 1;
            }
        }
        return 0;
    }
    public function isExpert($user){
        $roles = $user->roles->toArray();
        foreach($roles as $role)
        {
            if($role['id'] == 3)
            {
                return 1;
            }
        }
        return 0;
    }
    public function includeRoles(User $user){
        return $this->collection($user->roles()
                                         ->get(), new RoleTransformer());
    }
    public function includeservices(User $user){
        return $this->collection($user->services()
                                         ->get(), new ChildServiceTransformer());
    }
}

