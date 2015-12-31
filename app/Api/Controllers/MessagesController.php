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
    public function show($id)
    {
        $thread = Thread::where('subject', "Assignment-" .$id.Input::get('user_id'))->first();
        if(is_null($thread)){
            try {
                $thread = Thread::findOrFail($id);
            } catch (ModelNotFoundException $e) {

                return $this->error('The thread with ID: ' . $id . ' was not found.', 404);
            }
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Authorizer::getResourceOwnerId();
        /*$users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();*/
        $messages = $thread->messages;
        foreach ($messages as $message) {
            if ($message->user_id == $userId) {
                $message->update(['is_read' => 1]);
            }
        }
        $thread->markAsRead($userId);

        return compact('thread', 'messages');
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
        $input = Input::all();
       
             
        if(Input::has('assignment_id')){
            $thread = Thread::where('subject', "Assignment-" . Input::get('assignment_id').Input::get('user_id'))->first();
            if(is_null($thread)){
                $thread = Thread::create(
                    [
                        'subject' => "Assignment-" . Input::get("assignment_id").Input::get('user_id'),
                    ]
                );
            }
        }
//        else{
//            $thread = Thread::where('subject', "Admin-" . Authorizer::getResourceOwnerId())->first();
//            if (Input::has('recipients')) {
//                $thread = Thread::where('subject', "Admin-" . Input::get('recipients')[0])->first();
//                /* print_r("Admin-".Input::get('recipients')[0]);
//                 print_r($thread);
//                 die;*/
//            }
//            if (is_null($thread)) {
//                $thread = Thread::create(
//                    [
//                        'subject' => "Admin-" . Authorizer::getResourceOwnerId(),
//                    ]
//                );
//            }
//        }

        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id' => Authorizer::getResourceOwnerId(),
                'body' => $input['message'],
            ]
        );

        /*// Sender
        Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id'   => Authorizer::getResourceOwnerId(),
                'last_read' => new Carbon,
            ]
        );*/
        //sender
        $thread->addParticipants([Authorizer::getResourceOwnerId()]);

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants($input['recipients']);
        } else {
            $thread->addParticipants([$this->admin_id]);
        }

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
        return ["read"=>$thread->isUnread(Authorizer::getResourceOwnerId())];
    }
}

