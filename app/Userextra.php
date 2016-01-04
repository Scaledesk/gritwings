<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userextra extends Model
{

    protected $table      = 'user_extra';
    protected $primaryKey='id';
    protected $fillable   = [
        'salutation',
        'first_name',
        'last_name',
        'personal_email',
        'highest_degree',
        'major_specialization',
        'profession',
        'expert_area',
        'linkedin_url',
        'resume_url',
        'facebook_url',
        'user_id'
    ];
    public    $timestamps = false;
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
