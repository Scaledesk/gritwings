<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model {
	const EMAIL = 'email';
        const TOKEN = 'token';
        const CREATED_AT = 'created_at';
        
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token', 'created_at'];
	public $timestamps = false;


}
