<?php

namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * Class MessagesController
 * @package App\Api\Controllers
 */
class MessagesController extends Controller
{
    private $admin_id;

    /**
     * MessagesController constructor.
     */
    public function __construct()
    {
        $this->middleware('oauth');
        $this->admin_id = 18;

    }

    protected function model()
    {
        // TODO: Implement model() method.
    }

    protected function transformer()
    {
        // TODO: Implement transformer() method.
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        $currentUserId = Authorizer::getResourceOwnerId();

        // All threads, ignore deleted/archived participants
        //   $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
        $threads = Thread::forUser($currentUserId)->latest('updated_at')->get();


        // All threads that user is participating in, with new messages
        //$threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->get();

        return compact('threads', 'currentUserId');
    }

    /**
     * @return array
     */
    public function getNewThreads()
    {

        $currentUserId = Authorizer::getResourceOwnerId();

        $threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->get();

        return compact('threads', 'currentUserId');
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($subject)
    {

        try {
            $thread = Thread::where('subject',$subject)->first();
        } catch (ModelNotFoundException $e) {
            echo "error";
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Authorizer::getResourceOwnerId();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return  $thread->messages;
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {


        if(Input::has('subject')){
            $thread = Thread::where('subject', Input::get('subject'))->first();
            if(is_null($thread)){
                $thread = Thread::create(
                    [
                        'subject' => Input::get('subject'),
                    ]
                );
            }
        }

        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id' => Authorizer::getResourceOwnerId(),
                'body' => Input::get('body'),
            ]
        );




        return $this->successWithData('','',['thread_id'=>$thread->id]);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect('messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'body' => Input::get('message'),
            ]
        );

        // Add replier as a participant
        $participant = Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id' => Auth::user()->id,
            ]
        );
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants(Input::get('recipients'));
        }

        return redirect('messages/' . $id);
    }
   public function checkThread()
    {
        $thread_id = Input::get('thread_id');
        $thread = Thread::where('id', $thread_id)->first();
        unset($thread_id);
        /*print_r(Authorizer::getResourceOwnerId());
        die;*/
        return ["read"=>$thread->isUnread(Authorizer::getResourceOwnerId())];
    }
    public function makeRead($id){
        $thread=Thread::where('id',$id)->first();
        if(is_null($thread)){
            $this->setStatusCode(404);
            return $this->respondWithArray([
                'message'=>'Thread not found',
                'status_code'=>404
            ]);
        }else{
            $thread->markAsRead(Authorizer::getResourceOwnerId());
            $this->setStatusCode(200);
            return $this->respondWithArray([
                'message'=>'success',
                'status_code'=>200
            ]);
        }

    }

    public function getMessagesByThread($id){
        $thread = Thread::findOrFail($id);
        $thread->markAsRead(Authorizer::getResourceOwnerId());
        return $thread->messages;
    }

    public function createNewThread(){

        if(Thread::where('subject',Input::get('subject'))->first() == null)
        {
            $thread = Thread::create(
                [
                    'subject' => Input::get('subject'),
                ]
            );

            Participant::create(
                [
                    'thread_id' => $thread->id,
                    'user_id'   => Authorizer::getResourceOwnerId(),
                    'last_read' => new Carbon,
                ]
            );

            $thread->addParticipants([Input::get('participant_id')]);

            return $thread;
        }
        return Thread::where('subject',Input::get('subject'))->first()->get();
    }
}

