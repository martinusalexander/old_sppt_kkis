<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'distribution';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'date_time', 'deadline', 'media_id',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the announcements that will be announced in the distribution.
     */
    public function announcements() {
        return $this->belongsToMany('App\Announcement', 'announcement_distribution', 'distribution_id', 'announcement_id');
    }
    
    /**
     * Get the media used in the distribution.
     */
    public function media() {
        return $this->belongsTo('App\Media');
    }
}
