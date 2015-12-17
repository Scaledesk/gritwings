<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model {
	const ID = 'id';
        const RELATION_NAME = 'relation_name';
        const TABLE_NAME = 'table_name';
        
    protected $table = 'relations';
    protected $fillable = ['id', 'relation_name', 'table_name'];
	public $timestamps = false;


}
