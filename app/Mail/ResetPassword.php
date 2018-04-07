<?php

namespace App\Mail;
use App\User;
use Password;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    /**
     * The User instance
     * 
     * @var User
     */
    protected $user;
    
    /**
     * The token to activate the account
     * 
     * @var string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = $user->email_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
                    ->subject('['.config('app.name').'] Permintaan untuk atur ulang (reset) password')
                    ->view('user.email.resetpassword')
                    ->with([
                        'name' => $this->user->name,
                        'token' => $this->token,
                    ]);
    }
}
