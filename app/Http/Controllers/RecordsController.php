<?php namespace App\Http\Controllers;

use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RecordsController extends Controller
{

    public function index(){
        $records = Record::all();
        return Response::json($records);
    }

    public function store(Request $request){
        $record = new Record($request->all());
        $record->save();
        return $record;
    }
}
