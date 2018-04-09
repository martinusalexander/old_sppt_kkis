<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UpdateAnnouncementDistribution extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    /**
     * The User instance
     * 
     * @var User
     */
    protected $user;
    
    /**
     * The requester name
     * 
     * @var string
     */
    protected $requester_name;
    
    /**
     * The action (add/edit/delete)
     * 
     * @var string
     */
    protected $action;
    
    /**
     * The title of the announcement
     * 
     * @var title
     */
    protected $title;
    
    /**
     * The content of the announcement
     * An keyed array (mapping from media name to the corresponding content)
     * 
     * @var content
     */
    protected $content;
    
    /**
     * The date_time of the distribution
     * 
     * @var date_time
     */
    protected $date_time;
    
    /**
     * The supporting image of the announcement
     * 
     * @var date_time
     */
    protected $image_filepath;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $requester_name, $action, $title, $content, $date_time, $image_filepath = null)
    {
        $this->user = $user;
        $this->requester_name = $requester_name;
        $this->action = $action;
        $this->title = $title;
        $this->content = $content;
        $this->date_time = $date_time;
        $this->image_filepath = $image_filepath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->to($this->user->email)
                    ->subject('['.config('app.name').'] Permintaan untuk mendistribusikan pengumuman melalui media online')
                    ->view('announcementdistribution.email.update')
                    ->with([
                        'name' => $this->user->name,
                        'creator_name' => $this->requester_name,
                        'action' => $this->action,
                        'title' => $this->title,
                        'content' => $this->content,
                        'date_time' => $this->date_time,
                    ]);
        if ($this->image_filepath !== null) {
            $mail->attach($this->image_filepath);
        }
        return $mail;      
    }
}
