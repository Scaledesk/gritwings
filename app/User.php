<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    const ID                                = 'id';
    const NAME                              = 'name';
    const EMAIL                             = 'email';
    const PASSWORD                          = 'password';
    const CONFIRMED                         = 'confirmed';
    const CONFIRMATION_CODE                 = 'confirmation_code';
    const REMEMBER_TOKEN                    = 'remember_token';
    const CREATED_AT                        = 'created_at';
    const UPDATED_AT                        = 'updated_at';
    const MOBILE_NUMBER                     = 'mobile_number';
    const DESCRIPTION                       = 'description';
    const IMAGE                             = 'image';
    const BIRTH_DATE                        = 'birth_date';
    const GENDER                            = 'gender';
    const GOOGLE_ID                         = 'google_id';
    const SOCIAL_AUTH_PROVIDER              = 'social_auth_provider';
    const SOCIAL_AUTH_PROVIDER_ID           = 'social_auth_provider_id';
    const SOCIAL_AUTH_PROVIDER_ACCESS_TOKEN = 'social_auth_provider_access_token';
    const FACEBOOK_ID                       = 'facebook_id';

    protected $table      = 'users';
    protected $fillable   = [
        'id',
        'name',
        'email',
        'password',
        'confirmed',
        'confirmation_code',
        'remember_token',
        'created_at',
        'updated_at',
        'mobile_number',
        'description',
        'image',
        'birth_date',
        'gender',
        'google_id',
        'social_auth_provider',
        'social_auth_provider_id',
        'social_auth_provider_access_token',
        'facebook_id'
    ];
    public    $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function assignments()
    {
        return $this->hasMany('App\Assignment', 'user_id', 'id');
    }

    public function roleUsers()
    {
        return $this->hasMany('App\RoleUser', 'user_id', 'id');
    }
}
