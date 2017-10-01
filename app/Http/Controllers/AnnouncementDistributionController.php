<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use App\Announcement;
use App\Revision;

class AnnouncementDistributionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Update the announcement distribution based on the condition
     * 
     * @param $details
     * @return void
     */
    public function update($details) {
        $action = $details['action'];
        $id = $details['id'];
        if ($action === 'CREATE_ANNOUNCEMENT') {
            // TODO
        } else if ($action === 'EDIT_ANNOUNCEMENT') {
            // TODO
        } else if ($action === 'DELETE_ANNOUNCEMENT') {
            // TODO
        } else if ($action === 'CREATE_DISTRIBUTION') {
            // TODO
        } else if ($action === 'EDIT_DISTRIBUTION') {
            // TODO
        } else if ($action === 'DELETE_DISTRIBUTION') {
            // TODO
        }
        return;
    }
}
