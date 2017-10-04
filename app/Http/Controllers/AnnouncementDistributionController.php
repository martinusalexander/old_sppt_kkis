<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use App\AnnouncementDistribution;
use App\Announcement;
use App\Revision;
use App\Distribution;
use App\Media;

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
        // $id = $details['id'];
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
    
    /**
     * Display all announcements
     * 
     * @param Request $request
     * @param $distribution_id
     * @return redirect
     */
    public function view(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        if ($distribution_id === null) {
            $now = Carbon::now();
            $offline_media_ids = Media::where('is_online', false)->pluck('id')->toArray();
            $offline_distributions = Distribution::where('date_time', '>', $now)->whereIn('media_id', $offline_media_ids)->orderBy('date_time')->get();
            foreach ($offline_distributions as $distribution) {
                $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
            }
            $online_media_ids = Media::where('is_online', true)->pluck('id')->toArray();
            $online_distributions = Distribution::whereIn('media_id', $online_media_ids)->orderBy('description')->get();
            return view('announcementdistribution.index', ['offline_distributions' => $offline_distributions,
                                                           'online_distributions' => $online_distributions]);
        } else {
            $distribution = Distribution::where('id', $distribution_id)->first();
            if (!$distribution) {
                return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
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
            } else if (0 < $now_to_deadline_diff && $now_to_deadline_diff < 24 * 3600) {
                $distribution->status = 'MENDEKATI BATAS AKHIR (DEADLINE)';
            } else {
                $distribution->status = 'MENERIMA PENGUMUMAN';
            }
            $media_name = $distribution->media()->first()->name;
            $announcements = array();
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)->where('is_rejected', false)->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                                where('revision_no', $announcement_distribution->revision_no)->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                if ($media_name === 'Rotating Slide') {
                    $revision->description = null;
                } else if ($media_name === 'Pengumuman Misa') {
                    $revision->description = $revision->mass_announcement;
                } else if ($media_name === 'Flyer') {
                    $revision->description = null;
                } else if ($media_name === 'Bulletin Dombaku') {
                    $revision->description = $revision->bulletin;
                } else if ($media_name === 'Website') {
                    $revision->description = $revision->website;
                } else if ($media_name === 'Facebook') {
                    $revision->description = $revision->facebook;
                } else if ($media_name === 'Instagram') {
                    $revision->description = $revision->instagram;
                }
                array_push($announcements, $revision);
            }
            $rejected_announcements = array();
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)->where('is_rejected', true)->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                                where('revision_no', $announcement_distribution->revision_no)->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                if ($media_name === 'Rotating Slide') {
                    $revision->description = null;
                } else if ($media_name === 'Pengumuman Misa') {
                    $revision->description = $revision->mass_announcement;
                } else if ($media_name === 'Flyer') {
                    $revision->description = null;
                } else if ($media_name === 'Bulletin Dombaku') {
                    $revision->description = $revision->bulletin;
                } else if ($media_name === 'Website') {
                    $revision->description = $revision->website;
                } else if ($media_name === 'Facebook') {
                    $revision->description = $revision->facebook;
                } else if ($media_name === 'Instagram') {
                    $revision->description = $revision->instagram;
                }
                array_push($rejected_announcements, $revision);
            }
            return view('announcementdistribution.view', ['distribution' => $distribution,
                                                          'announcements' => $announcements,
                                                          'rejected_announcements' => $rejected_announcements]);
        }
    }
    
    /**
     * Display the manage announcement in distribution page
     * 
     * @param Request $request
     * @param $distribution_id
     * @return redirect
     */
    public function manage(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        if ($distribution_id === null) {
            $now = Carbon::now();
            $offline_media_ids = Media::where('is_online', false)->pluck('id')->toArray();
            $offline_distributions = Distribution::where('date_time', '>', $now)->whereIn('media_id', $offline_media_ids)->orderBy('date_time')->get();
            foreach ($offline_distributions as $distribution) {
                $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
            }
            $online_media_ids = Media::where('is_online', true)->pluck('id')->toArray();
            $online_distributions = Distribution::whereIn('media_id', $online_media_ids)->orderBy('description')->get();
            return view('announcementdistribution.manage.distributions', ['offline_distributions' => $offline_distributions,
                                                                          'online_distributions' => $online_distributions]);
        } else {
            $distribution = Distribution::where('id', $distribution_id)->first();
            if (!$distribution) {
                return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
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
            } else if (0 < $now_to_deadline_diff && $now_to_deadline_diff < 24 * 3600) {
                $distribution->status = 'MENDEKATI BATAS AKHIR (DEADLINE)';
            } else {
                $distribution->status = 'MENERIMA PENGUMUMAN';
            }
            $media_name = $distribution->media()->first()->name;
            $announcements = array();
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)->where('is_rejected', false)->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                                where('revision_no', $announcement_distribution->revision_no)->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                if ($media_name === 'Rotating Slide') {
                    $revision->description = null;
                } else if ($media_name === 'Pengumuman Misa') {
                    $revision->description = $revision->mass_announcement;
                } else if ($media_name === 'Flyer') {
                    $revision->description = null;
                } else if ($media_name === 'Bulletin Dombaku') {
                    $revision->description = $revision->bulletin;
                } else if ($media_name === 'Website') {
                    $revision->description = $revision->website;
                } else if ($media_name === 'Facebook') {
                    $revision->description = $revision->facebook;
                } else if ($media_name === 'Instagram') {
                    $revision->description = $revision->instagram;
                }
                array_push($announcements, $revision);
            }
            $rejected_announcements = array();
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)->where('is_rejected', true)->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)->
                                where('revision_no', $announcement_distribution->revision_no)->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                if ($media_name === 'Rotating Slide') {
                    $revision->description = null;
                } else if ($media_name === 'Pengumuman Misa') {
                    $revision->description = $revision->mass_announcement;
                } else if ($media_name === 'Flyer') {
                    $revision->description = null;
                } else if ($media_name === 'Bulletin Dombaku') {
                    $revision->description = $revision->bulletin;
                } else if ($media_name === 'Website') {
                    $revision->description = $revision->website;
                } else if ($media_name === 'Facebook') {
                    $revision->description = $revision->facebook;
                } else if ($media_name === 'Instagram') {
                    $revision->description = $revision->instagram;
                }
                array_push($rejected_announcements, $revision);
            }
            return view('announcementdistribution.manage.announcements', ['distribution' => $distribution,
                                                                          'announcements' => $announcements,
                                                                          'rejected_announcements' => $rejected_announcements]);
        }
    }
    
    /**
     * Update the announcement distribution to the latest revision
     * 
     * @param Request $request
     * @return redirect
     */
    public function update_to_latest_version(Request $request, $announcement_distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        // Invalid URL
        if ($announcement_distribution_id === null) {
            return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $announcement_distribution = AnnouncementDistribution::where('id', $announcement_distribution_id)->first();
        // Invalid URL
        if (!$announcement_distribution) {
            return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $revision_number = $announcement_distribution->announcement()->first()->current_revision_no;
        AnnouncementDistribution::where('id', $announcement_distribution_id)->update([
            'revision_no' => $revision_number,
        ]);
        return redirect('/announcementdistribution/manage/'.$announcement_distribution->distribution_id)->with('success_message', 'Pengumuman tersebut dalam distribusi ini telah diubah ke versi terbaru.');
    }
    
    /**
     * Reject the announcement distribution (e.g. because of quota)
     * 
     * @param Request $request
     * @return redirect
     */
    public function reject(Request $request, $announcement_distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        // Invalid URL
        if ($announcement_distribution_id === null) {
            return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $announcement_distribution = AnnouncementDistribution::where('id', $announcement_distribution_id)->first();
        // Invalid URL
        if (!$announcement_distribution) {
            return redirect('/announcementdistribution/manage')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $is_rejected = $announcement_distribution->is_rejected;
        AnnouncementDistribution::where('id', $announcement_distribution_id)->update([
            'is_rejected' => !$is_rejected,
        ]);
        if (!$is_rejected) {
            $success_message = 'Pengumuman tersebut telah berhasil ditolak dalam distribusi ini.';
        } else {
            $success_message = 'Pengumuman yang sebelumnya ditolak tersebut telah dimasukkan kembali dalam distribusi ini.';
        }
        return redirect('/announcementdistribution/manage/'.$announcement_distribution->distribution_id)->with('success_message', $success_message);
    }
}
