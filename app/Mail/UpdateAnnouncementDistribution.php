<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UpdateAnnouncementDistribution extends Mailable
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
     * The name of the media
     * 
     * @var media_name
     */
    protected $media_name;
    
    /**
     * The title of the announcement
     * 
     * @var title
     */
    protected $title;
    
    /**
     * The description of the announcement
     * 
     * @var media_name
     */
    protected $description;
    
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
    public function __construct(User $user, $requester_name, $action, $media_name, $title, $description, $date_time, $image_filepath = null)
    {
        $this->user = $user;
        $this->requester_name = $requester_name;
        $this->action = $action;
        $this->media_name = $media_name;
        $this->title = $title;
        $this->description = $description;
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
                        'media_name' => $this->media_name,
                        'title' => $this->title,
                        'description' => $this->description,
                        'date_time' => $this->date_time,
                    ]);
        if ($this->image_filepath !== null) {
            $mail->attach($this->image_filepath);
        }
        return $mail;      
    }
}
