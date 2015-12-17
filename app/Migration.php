<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Migration extends Model {
	const MIGRATION = 'migration';
        const BATCH = 'batch';
        
    protected $table = 'migrations';
    protected $fillable = ['migration', 'batch'];
	public $timestamps = false;


}
