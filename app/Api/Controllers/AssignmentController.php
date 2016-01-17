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
        $this->middleware('oauth',['except'=>['doPayment','completionDoPayment','successPayment','completionFailurePayment','completionSuccessPayment','failurePayment',]]);

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

    public function getAllAssignmentsByStatus($statusId,$serviceId)
    {

        $with = $this->getEagerLoad();

        $items = $this->model->where('status_id',$statusId)->whereHas('childService', function ($query) use($serviceId) {
            $query->where('parent_service_id', $serviceId);
        })->get();
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
    public function doPayment($assignment_id,$user_id){
        $transaction=Assignment_Transaction::where('assignment_id',$assignment_id)->where('payment_type','booking_amount')->first();
        $user=User::where('id',$user_id)->first();
        $user_extra=Userextra::where('user_id',$user_id)->first();
        $assignment=Assignment::where('id',$assignment_id)->first();
        unset($user_id);
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
        "amount"=>$amount,
        "surl"=>"http://54.200.205.117/api/v1/payment_success/",
        "furl"=>"http://54.200.205.117/api/v1/payment_failure/",
//            "surl"=>"http://localhost:8000/api/v1/payment_success/",
//        "furl"=>"http://localhost:8000/api/v1/payment_failure/"
        ]);
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
//        return Redirect::away('http://localhost:3000/#/user-dashboard?a=active-assignments&payment=success');
        return Redirect::away('http://angular.gritwings.com/#/user-dashboard?a=active-assignments&payment=success');

    }
    public function failurePayment(){
        return Redirect::away('http://angular.gritwings.com/#/user-dashboard?a=active-assignments&payment=failure');
//        return Redirect::away('http://localhost:3000/#/user-dashboard?a=active-assignments&payment=failure');
    }
    public function completionDoPayment($assignment_id,$user_id){
        $transaction=Assignment_Transaction::where('assignment_id',$assignment_id)->where('payment_type','completion_amount')->first();
        $user=User::where('id',$user_id)->first();
        $user_extra=Userextra::where('user_id',$user_id)->first();
        $assignment=Assignment::where('id',$assignment_id)->first();
        unset($user_id);
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
        "amount"=>$amount,
//        "surl"=>"http://localhost:8000/api/v1/completionPayment_success/",
//            "furl"=>"http://localhost:8000/api/v1/completionPayment_failure/"
            "surl"=>"http://54.200.205.117/api/v1/completionPayment_success/",
            "furl"=>"http://54.200.205.117/api/v1/completionPayment_failure/",]);
//]);
    }

    public function completionSuccessPayment(){
        /*echo "done";*/
        $assignment_id=$_POST['productinfo'];
        $transaction=Assignment_Transaction::where('assignment_id',$assignment_id)->where('payment_type','booking_amount')->first();
        $transaction->update([
            'status'=>'payment_done',
            'date'=>date('Y-m-d'),
            'transaction_id'=>$_POST['txnid']
        ]);
        $assignment=Assignment::where('id',$assignment_id)->first();
        $assignment->update(['status_id'=>6]);
        return Redirect::away('http://angular.gritwings.com/#/user-dashboard?a=active-assignments&payment=success');
//        return Redirect::away('http://localhost:3000/#/user-dashboard?a=active-assignments&payment=success');

    }
    public function completionFailurePayment(){
        return Redirect::away('http://angular.gritwings.com/#/user-dashboard?a=active-assignments&payment=failure');
//        return Redirect::away('http://localhost:3000/#/user-dashboard?a=active-assignments&payment=failure');
    }
    public function insertTransactions($assignMent_id){
        $assignment=Assignment::where('id',$assignMent_id)->first();
        if(is_null($assignment)){
            unset($assignment,$assignMent_id);
            $this->setStatusCode(404);
            return $this->respondWithArray([
                'message'=>"Assignment not found",
                'status_code'=>404
            ]);
        }else{
            Assignment_Transaction::create([
                'assignment_id'=>$assignMent_id,
                'payment_type'=>'booking_amount',
                'amount'=>$assignment->booking_amount,
            ]);
            Assignment_Transaction::create([
                'assignment_id'=>$assignMent_id,
                'payment_type'=>'completion_amount',
                'amount'=>$assignment->completion_amount,
            ]);
        }
        $this->setStatusCode(200);
        return $this->respondWithArray([
            'message'=>"success",
            'status_code'=>200
        ]);
    }
}
