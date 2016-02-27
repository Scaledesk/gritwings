<?php namespace App\Api\Transformers;

use App\User;
use Illuminate\Support\Facades\Input;
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
         'child_services'
        ];

    public function transform(User $item)
    {
        return [
				'id'  =>  $item->id,
            'name'  =>  $item->name,
            'email'  =>  $item->email,
            'confirmed'  =>  $item->confirmed,
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
             'is_expert' => $this->isExpert($item),
             'affiliation' => $item->affiliation,
        'highest_degree'=> $item->highest_degree,
        'specialization'=> $item->specialization,
        'profession'=> $item->profession,
        'linkedin_profile'=> $item->linkedin_profile,
        'research_gate_id'=> $item->research_gate_id,
        'resume'=> $item->resume,
            'rating' => $item->rating

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
    public function includeChildServices(User $user){
        return $this->collection($user->childServices()
            ->get(), new ChildServiceTransformer());
    }
    public function userExtraTransformer(){
        return [
            'salutation'=>Input::get('salutation',NULL),
            'first_name'=>Input::get('first_name',NULL),
            'last_name'=>Input::get('last_name',NULL),
            'personal_email'=>Input::get('personal_email',NULL),
            'highest_degree'=>Input::get('highest_degree',NULL),
            'major_specialization'=>Input::get('major_specialization',NULL),
            'profession'=>Input::get('profession',NULL),
            'expert_area'=>Input::get('expert_area',NULL),
            'linkedin_url'=>Input::get('linkedin_url',NULL),
            'facebook_url'=>Input::get('facebook_url',NULL),
            'resume_url'=>Input::get('resume_url',NULL),
            'research_gate_id'=>Input::get('research_gate_id',NULL),
        ];
    }
}

