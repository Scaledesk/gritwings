<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use App\User;

class SendConfirmationEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param  User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer $mailer
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        Mail::send('email.confirmation', ['name' => $user->name,], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Confirmed');
        });
    }
}
