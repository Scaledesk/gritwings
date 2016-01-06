<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment_Transaction extends Model
{
    //
    protected $table = 'transactions';
    protected $fillable = ['assignment_id', 'payment_type', 'status', 'date', 'amount','transaction_id'];
    protected $primaryKey='id';
    public $timestamps=false;

    public function assignment(){
        return $this->belongsTo('App\Assignment','assignment_id');
    }
}
