<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Assignment;
use App\Api\Transformers\AssignmentTransformer;
use App\Assignment_Transaction;
use App\Userextra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use League\Fractal\Manager;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\User;
class AssignmentController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Assignment;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new AssignmentTransformer;
    }

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('oauth',['except'=>['doPayment','successPayment']]);

        $this->model       = $this->model();
        $this->transformer = $this->transformer();

        $this->fractal = new Manager();
        $this->fractal->setSerializer($this->serializer());

        $this->request = $request;

        if ($this->request->has('include')) {
            $this->fractal->parseIncludes(camel_case($this->request->input('include')));
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/{resource}.
     *
     * @return Response
     */
    public function store()
    {

        $data = $this->request->json()
                              ->get($this->resourceKeySingular);

        if (!$data) {
            return $this->errorWrongArgs('Empty data');
        }
        $data['user_id'] = Authorizer::getResourceOwnerId();
        $validator       = Validator::make($data, $this->rulesForCreate());
        if ($validator->fails()) {
            return $this->errorWrongArgs($validator->messages());
        }

        $this->unguardIfNeeded();

        $item = $this->model->create($data);
      /*  Assignment_Transaction::create([

        ]);*/

        return $this->respondWithItem($item);
    }

    public function getUserAssignmentsByStatus($statusId)
    {
        $userId = Authorizer::getResourceOwnerId();


        $items = $this->model->where('user_id',$userId)->where('status_id',$statusId)->get();
        return $this->respondWithCollection($items);
    }

    public function getAllAssignmentsByStatus($statusId)
    {

        $with = $this->getEagerLoad();

        $items = $this->model->where('status_id',$statusId)->get();
        return $this->respondWithCollection($items);
    }

    public function getExpertUndergoingAssignments(){
        $userId = Authorizer::getResourceOwnerId();
        $status = [2,3,4,5,6];

        $items = $this->model->whereIn('status_id',$status)->whereHas('bid', function ($query) {
            $query->where('user_id', Authorizer::getResourceOwnerId());
        })->get();
        return $this->respondWithCollection($items);
    }

    public function getExpertAssignmentsByStatus($statusId){
        $userId = Authorizer::getResourceOwnerId();

        $items = $this->model->where('status_id',$statusId)->whereHas('bid', function ($query) {
            $query->where('user_id', Authorizer::getResourceOwnerId());
        })->get();
        return $this->respondWithCollection($items);
    }

    public function getExpertAvailableAssignments(){
        $userId = Authorizer::getResourceOwnerId();
        $services = User::findorFail($userId)->childServices()->get();
        $arr = [];
        foreach($services as $service){
        array_push($arr,$service['id']);
        }

        $items = $this->model->where('status_id',7)->whereIn('child_service_id',$arr)->get();
        return $this->respondWithCollection($items);
    }

    public function updateAssignmentBidders($assignmentId){

        $data = Input::get('data');
        $bidders = $data['bidders'];
        DB::delete('delete from bidder_assignment where assignment_id = ?',[$assignmentId]);
        foreach($bidders as $bidder){
            DB::insert('insert into bidder_assignment (assignment_id, bidder_id) values (?, ?)', [$assignmentId, $bidder]);
        }
        return $this->respondWithItem($this->model()->findorFail($assignmentId));
    }
    public function doPayment($assignment_id){
        $transaction=Assignment_Transaction::where('assignment_id',$assignment_id)->where('payment_type','booking_amount')->first();
        $userId = /*Authorizer::getResourceOwnerId();*/59;
        $user=User::where('id',$userId)->first();
        $user_extra=Userextra::where('user_id',$userId)->first();
        $assignment=Assignment::where('id',$assignment_id)->first();
        unset($userId);
        $email=$user->email;
        $mobile=$user->mobile_number;
        $first_name=/*$user_extra->first_name*/'Tushar';
        $last_name=/*$user_extra->last_name*/"Agarwal";
        $amount=$transaction->amount;
        $product_info=$assignment_id;
        unset($assignment,$user,$user_extra,$transaction);
        return \Illuminate\Support\Facades\View::make('payumoney',[
            "email"=>$email,
            "mobile"=>$mobile,
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "product_info"=>$product_info,
        "amount"=>$amount]);
    }

    public function successPayment(){
        /*echo "done";*/
        $assignment_id=$_POST['productinfo'];
        $transaction=Assignment_Transaction::where('assignment_id',$assignment_id)->where('payment_type','booking_amount')->first();
        $transaction->update([
            'status'=>'payment_done',
            'date'=>date('Y-m-d'),
            'transaction_id'=>$_POST['txnid']
        ]);
        $assignment=Assignment::where('id',$assignment_id)->first();
        $assignment->update(['status_id'=>3]);
        return Redirect::away('http://localhost:3000/#/payment/success');

    }
    public function failurePayment(){
        return Redirect::away('http://localhost:3000/#/payment/failure');
    }
}
