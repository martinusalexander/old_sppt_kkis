<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'revision';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'announcement_id', 'revision_no', 'title', 
        'description', 'date_time', 'is_routine', 'image_path',
        'rotating_slide', 'mass_announcement', 'flyer',
        'bulletin', 'facebook', 'instagram', 'submitter_id'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the user that submitted the revision.
     */
    public function submitter() {
        return $this->belongsTo('App\User', 'submitter_id');
    }
    
    /**
     * Get the announcement which owns the revision.
     */
    public function announcement() {
        return $this->belongsTo('App\Announcement');
    }
}
