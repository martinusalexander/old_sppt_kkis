<?php

namespace App\Mail;
use App\User;
use Password;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordChanged extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The User instance
     * 
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
                    ->subject('['.config('app.name').'] Password Anda telah berhasil diubah')
                    ->view('user.email.passwordchanged')
                    ->with([
                        'name' => $this->user->name,
                    ]);
    }
}
