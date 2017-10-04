<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnnouncementDistribution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcement_distribution';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'announcement_id', 'distribution_id', 'revision_no', 'is_rejected',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the announcement that is associated to the announcement distribution.
     */
    public function announcement() {
        return $this->belongsTo('App\Announcement', 'announcement_id');
    }
    
    /**
     * Get the distribution that is associated to the announcement distribution.
     */
    public function distribution() {
        return $this->belongsTo('App\Distribution', 'distribution_id');
    }
    
}
