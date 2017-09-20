<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'organization_name', 'email', 'password', 'email_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_token',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the announcements created by the user.
     */
    public function announcements_created() {
        return $this->hasMany('App\Announcement', 'creator_id');
    }
    
    /**
     * Get the announcements last edited by the user.
     */
    public function announcements_last_edited() {
        return $this->hasMany('App\Announcement', 'last_editor_id');
    }
    
    /**
     * Get the announcements approved by the user.
     */
    public function announcements_approved() {
        return $this->hasMany('App\Announcement', 'approver_id');
    }
    
    /**
     * Get the revisions submitted by the user.
     */
    public function revisions_submitted() {
        return $this->hasMany('App\Revision', 'submitter_id');
    }
    
    
}
