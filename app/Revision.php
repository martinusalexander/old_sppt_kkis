<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Media;

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
    
    /**
     * Get the customize description based on the media ID.
     */
    public function get_description_by_media_id ($media_id) {
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
    public function get_description_by_media_name ($media_name) {
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
