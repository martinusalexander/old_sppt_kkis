<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Media;

class Announcement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcement';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'date_time', 'is_routine', 
        'image_path', 'rotating_slide', 'mass_announcement', 
        'flyer', 'bulletin', 'facebook', 'instagram', 
        'creator_id',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the user that created the announcement.
     */
    public function creator() {
        return $this->belongsTo('App\User', 'creator_id');
    }
    
    /**
     * Get the last user that edited the announcement.
     */
    public function last_editor() {
        return $this->belongsTo('App\User', 'last_editor_id');
    }
    
    /**
     * Get the user that approved the announcement.
     */
    public function approver() {
        return $this->belongsTo('App\User', 'approver_id');
    }
    
    /**
     * Get the revisions that belongs to the announcement.
     */
    public function revisions() {
        return $this->hasMany('App\Revision', 'announcement_id');
    }
    
    /**
     * Get the distributions in which the announcement will be announced.
     */
    public function distributions() {
        return $this->belongsToMany('App\Distribution', 'announcement_distribution', 'announcement_id', 'distribution_id');
    }
    
    /**
     * Get the customize description based on the media ID.
     */
    public function get_description_by_media_id (string $media_id) {
        $media_name = Media::where('id', $media_id)->first()->name;
        if ($media_name === 'Rotating Slide') {
            $result = null;
        } else if ($media_name === 'Pengumuman Misa') {
            $result = $this->mass_announcement;
        } else if ($media_name === 'Flyer') {
            $result = null;
        } else if ($media_name === 'Bulletin Dombaku') {
            $result = $this->bulletin;
        } else if ($media_name === 'Website') {
            $result = $this->website;
        } else if ($media_name === 'Facebook') {
            $result = $this->facebook;
        } else if ($media_name === 'Instagram') {
            $result = $this->instagram;
        } else {
            $result = $this->description;
        }
        return $result;
    }
    
    /**
     * Get the customize description based on the media name.
     */
    public function get_description_by_media (string $media_name) {
        if ($media_name === 'Rotating Slide') {
            $result = null;
        } else if ($media_name === 'Pengumuman Misa') {
            $result = $this->mass_announcement;
        } else if ($media_name === 'Flyer') {
            $result = null;
        } else if ($media_name === 'Bulletin Dombaku') {
            $result = $this->bulletin;
        } else if ($media_name === 'Website') {
            $result = $this->website;
        } else if ($media_name === 'Facebook') {
            $result = $this->facebook;
        } else if ($media_name === 'Instagram') {
            $result = $this->instagram;
        } else {
            $result = $this->description;
        }
        return $result;
    }
    
    
}
