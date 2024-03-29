<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use App\AnnouncementDistribution;
use App\Revision;
use App\Distribution;
use App\Media;

class DistributionController extends Controller
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
     * Display the list of distributions
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        $now = Carbon::now();
        $ten_days_before = $now->subDays(10);
        $offline_media_ids = Media::where('is_online', false)->pluck('id')->toArray();
        $offline_distributions = Distribution::where('date_time', '>', $ten_days_before)
                                             ->whereIn('media_id', $offline_media_ids)
                                             ->orderBy('date_time')
                                             ->get();
        foreach ($offline_distributions as $distribution) {
            $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
            $distribution->deadline = Carbon::parse($distribution->deadline)->format('l, j F Y, g:i a');
            $distribution->media_name = $distribution->media()->first()->name;
            $deadline = Carbon::parse($distribution->deadline);
            $now = Carbon::now();
            $now_to_deadline_diff = $now->diffInSeconds($deadline, false);
            if ($now_to_deadline_diff < 0) {
                $distribution->status = 'FINAL';
            } elseif (0 < $now_to_deadline_diff && $now_to_deadline_diff < 24 * 3600) {
                $distribution->status = 'MENDEKATI BATAS AKHIR (DEADLINE)';
            } else {
                $distribution->status = 'MENERIMA PENGUMUMAN';
            }
        }
        $online_media_ids = Media::where('is_online', true)->pluck('id')->toArray();
        $online_distributions = Distribution::whereIn('media_id', $online_media_ids)->orderBy('description')->get();
        return view('distribution.index', ['offline_distributions' => $offline_distributions,
                                           'online_distributions' => $online_distributions]);
    }
    
    /**
     * Handle view distribution attempt
     * 
     * @param Request $request
     * @param $distribution_id
     * @return redirect
     */
    public function view(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        // Invalid URL
        if ($distribution_id === null) {
            abort(404);
        }
        $distribution = Distribution::where('id', $distribution_id)->first();
        // Invalid URL
        if (!$distribution) {
            abort(404);
        }
        $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
        $distribution->deadline = Carbon::parse($distribution->deadline)->format('l, j F Y, g:i a');
        $distribution->media_name = $distribution->media()->first()->name;
        $distribution->is_online = $distribution->media()->first()->is_online;
        $deadline = Carbon::parse($distribution->deadline);
        $now = Carbon::now();
        $now_to_deadline_diff = $now->diffInSeconds($deadline, false);
        if ($now_to_deadline_diff < 0) {
            $distribution->status = 'FINAL';
        } elseif (0 < $now_to_deadline_diff && $now_to_deadline_diff < 24 * 3600) {
            $distribution->status = 'MENDEKATI BATAS AKHIR (DEADLINE)';
        } else {
            $distribution->status = 'MENERIMA PENGUMUMAN';
        }
        $media_name = $distribution->media()->first()->name;
        $announcements = array();
        $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                              ->where('is_rejected', false)
                                                              ->get();            
        foreach ($announcement_distributions as $announcement_distribution) {
            // Append revision instead of the announcement
            $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                            where('revision_no', $announcement_distribution->revision_no)->first();
            $revision->announcement_distribution_id = $announcement_distribution->id;
            if ($revision->image_path !== null) {
                $revision->image_path = Storage::url($revision->image_path);
            }
            $revision->description = $revision->get_description_by_media_name($media_name);
            array_push($announcements, $revision);
        }
        $rejected_announcements = array();
        $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                              ->where('is_rejected', true)
                                                              ->get();            
        foreach ($announcement_distributions as $announcement_distribution) {
            // Append revision instead of the announcement
            $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                            where('revision_no', $announcement_distribution->revision_no)->first();
            $revision->announcement_distribution_id = $announcement_distribution->id;
            if ($revision->image_path !== null) {
                $revision->image_path = Storage::url($revision->image_path);
            }
            $revision->description = $revision->get_description_by_media_name($media_name);
            array_push($rejected_announcements, $revision);
        }
        return view('distribution.view', ['distribution' => $distribution,
                                          'announcements' => $announcements,
                                          'rejected_announcements' => $rejected_announcements]);
    }
    
    /**
     * Handle distribution creation attempt
     * 
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        if ($request->isMethod('get')) {
            $media = Media::where('is_online', false)->get();
            return view('distribution.create', ['media' => $media]);
        } else {
            $description = $request->input('description');
            $date_time = $request->input('date-time');
            $deadline_time = $request->input('deadline');
            $media_id = $request->input('media');
            // Cannot create distribution for online media
            if (Media::where('id', $media_id)->first()->is_online) {
                abort(403);
            }
            // Convert dates to database format
            $date_time = Carbon::parse($date_time)->format('Y-m-d H:i:s');
            $deadline_time = Carbon::parse($deadline_time)->format('Y-m-d H:i:s');
            $distribution = Distribution::create([
                'description' => $description,
                'date_time' => $date_time,
                'deadline' => $deadline_time,
                'media_id' => $media_id,
            ]);
            $update_announcement_distribution_details = array(
                'action' => 'CREATE_DISTRIBUTION',
                'distribution' => $distribution,
            );
            // Must call the function in another controller non-statically
            // Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
            return redirect('/distribution', 303)->with('success_message', 'Distribusi telah berhasil dibuat.');
        }        
    }
    
    /**
     * Handle distribution creation attempt
     * 
     * @param Request $request
     * @param $distribution_id
     * @return Response
     */
    public function edit(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        if ($request->isMethod('get')) {
            // Invalid URL
            if ($distribution_id === null) {
                abort(404);
            }
            $distribution = Distribution::where('id', $distribution_id)->first();
            // Invalid URL
            if (!$distribution) {
                abort(404);
            }
            // Cannot edit distribution for online media
            if ($distribution->media()->first()->is_online) {
                abort(403);
            }
            $distribution->date_time = Carbon::parse($distribution->date_time)->format('m/d/Y g:i A');
            $distribution->deadline = Carbon::parse($distribution->deadline)->format('m/d/Y g:i A');
            $media = Media::where('is_online', false)->get();
            return view('distribution.edit', ['distribution' => $distribution, 'media' => $media]);
        } else {
            $id = $request->input('id');
            $description = $request->input('description');
            $date_time = $request->input('date-time');
            $deadline_time = $request->input('deadline');
            $media_id = $request->input('media');
            // Convert dates to database format
            $date_time = Carbon::parse($date_time)->format('Y-m-d H:i:s');
            $deadline_time = Carbon::parse($deadline_time)->format('Y-m-d H:i:s');
            $old_distribution = Distribution::where('id', $id)->first();
            Distribution::where('id', $id)->update([
                'description' => $description,
                'date_time' => $date_time,
                'deadline' => $deadline_time,
                'media_id' => $media_id,
            ]);
            $new_distribution = Distribution::where('id', $id)->first();
            $update_announcement_distribution_details = array(
                'action' => 'EDIT_DISTRIBUTION',
                'old_distribution' => $old_distribution,
                'new_distribution' => $new_distribution,
            );
            // Must call the function in another controller non-statically
            // Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
            return redirect('/distribution', 303)->with('success_message', 'Distribusi telah berhasil diubah.');
        }        
    }
    
    /**
     * Handle distribution deletion attempt
     * 
     * @param Request $request
     * @param $distribution_id
     * @return Response
     */
    public function delete(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        // Invalid URL
        if ($distribution_id === null) {
            abort(404);
        }
        $distribution = Distribution::where('id', $distribution_id)->first();
        // Invalid URL
        if (!$distribution) {
            abort(404);
        }
        // Cannot delete distribution for online media
        if ($distribution->media()->first()->is_online) {
            abort(403);
        }
        $update_announcement_distribution_details = array(
            'action' => 'DELETE_DISTRIBUTION',
            'distribution' => $distribution,
        );
        // Must call the function in another controller non-statically
        // Reference: https://stackoverflow.com/a/19694064
        (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
        $distribution->delete();
        return redirect('/distribution', 303)->with('success_message', 'Distribusi Anda telah berhasil dihapus.');
    }
}
