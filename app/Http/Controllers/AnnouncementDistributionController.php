<?php

namespace App\Http\Controllers;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Auth;
use Carbon\Carbon;
use App\AnnouncementDistribution;
use App\User;
use App\Announcement;
use App\Revision;
use App\Distribution;
use App\Media;
use App\Mail\UpdateAnnouncementDistribution;

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
        // APPROVE, EDIT, DELETE
        if ($action === 'APPROVE_ANNOUNCEMENT') {
            $now = Carbon::now();
            $announcement = $details['announcement'];
            if (AnnouncementDistribution::where('announcement_id', $announcement->id)->first() == null) {
                $action = 'membuat';
            } else {
                $action = 'mengubah';
            }
            if ($announcement->is_routine) {
                $announcing_duration = 35;
            } else {
                // Non-routine event has longer duration to announce
                $announcing_duration = 70;
            }
            $online_media = array();
            $offline_media = array();
            if ($announcement->rotating_slide != false) {
                array_push($offline_media, "Rotating Slide");
            }
            if ($announcement->mass_announcement !== null) {
                array_push($offline_media, "Pengumuman Misa");
            }
            if ($announcement->flyer != false) {
                array_push($offline_media, "Flyer");
            }
            if ($announcement->bulletin !== null) {
                array_push($offline_media, "Bulletin Dombaku");
            }
            if ($announcement->website !== null) {
                array_push($online_media, "Website");
            }
            if ($announcement->facebook !== null) {
                array_push($online_media, "Facebook");
            }
            if ($announcement->instagram !== null) {
                array_push($online_media, "Instagram");
            }
            // Proceed with offline distributions
            $offline_media_ids = Media::whereIn('name', $offline_media)->pluck('id')->toArray();
            if (AnnouncementDistribution::where('announcement_id', $announcement->id)->first() == null) {
                $offline_distributions = Distribution::where('date_time', '>', Carbon::parse($announcement->date_time)->subDays($announcing_duration))
                                                     ->where('date_time', '<', $announcement->date_time)
                                                     ->where('deadline', '>', $announcement->created_at)
                                                     ->whereIn('media_id', $offline_media_ids)
                                                     ->get();
            } else {
                $last_updated_time = AnnouncementDistribution::where('announcement_id', $announcement->id) 
                                                             ->whereNull('revision_no')
                                                             ->min('updated_at');
                $offline_distributions = Distribution::where('date_time', '>', Carbon::parse($announcement->date_time)->subDays($announcing_duration))
                                                     ->where('date_time', '<', $announcement->date_time)
                                                     ->where('deadline', '>', $last_updated_time)
                                                     ->whereIn('media_id', $offline_media_ids)
                                                     ->get();
            }
            foreach ($offline_distributions as $distribution) {
                AnnouncementDistribution::updateOrCreate([
                    'announcement_id' => $announcement->id,
                    'distribution_id' => $distribution->id,
                ], [
                    'revision_no' => $announcement->current_revision_no,
                ]);
            }
            // Proceed with online distributions
            $online_media_ids = Media::whereIn('name', $online_media)->pluck('id')->toArray();
            $online_distributions = Distribution::whereIn('media_id', $online_media_ids)
                                         ->get();
            foreach ($online_distributions as $distribution) {
                AnnouncementDistribution::updateOrCreate([
                    'announcement_id' => $announcement->id,
                    'distribution_id' => $distribution->id,
                ], [
                    'revision_no' => $announcement->current_revision_no,
                ]);
            }
            // Send the request of distributing using online media to admins
            $admins = User::where('is_admin', true)->get();
            $creator_name = User::where('id', $announcement->creator_id)->first()->name;
            if (Carbon::parse($announcement->date_time)->subDays($announcing_duration)->diffInSeconds($now, false) < 0) {
                $date_time = Carbon::parse($announcement->date_time)->subDays($announcing_duration)->format('l, j F Y, g:i a');
            } else {
                $date_time = Carbon::now()->format('l, j F Y, g:i a');
            }
            if ($announcement->image_path !== null) {
                $image_path = storage_path('/app/'.$announcement->image_path);
            } else {
                $image_path = null;
            }
            foreach ($online_media as $media) {
                foreach ($admins as $user) {
                    Mail::to($user)->send(new UpdateAnnouncementDistribution($user, $creator_name, $action, $media, 
                                                                             $announcement->title, $announcement->description, 
                                                                             $date_time, $image_path));
                }
            }        
        } elseif ($action === 'EDIT_ANNOUNCEMENT') {
            $now = Carbon::now();
            $announcement = $details['announcement'];
            // Update the revision number offline announcement distributions which are not final yet to null
            $not_deadline_ids = Distribution::where('deadline', '>', $now->format('Y-m-d H:i:s'))
                                            ->pluck('id')
                                            ->toArray();
            AnnouncementDistribution::where('announcement_id', $announcement->id)
                                    ->whereIn('distribution_id', $not_deadline_ids)
                                    ->update([
                                        'revision_no' => null,
                                    ]);
            // Update revision number all online announcement distributions to null
            $online_media_ids = Media::where('is_online', true)->pluck('id')->toArray();
            $online_distribution_ids = Distribution::whereIn('media_id', $online_media_ids)->pluck('id')->toArray();
            AnnouncementDistribution::where('announcement_id', $announcement->id)
                                    ->whereIn('distribution_id', $online_distribution_ids)
                                    ->update([
                                        'revision_no' => null,
                                    ]);
        } elseif ($action === 'DELETE_ANNOUNCEMENT') {
            // Nothing to do
            // The deletion is handle by the database (onDelete cascade)
        } elseif ($action === 'CREATE_DISTRIBUTION' || $action === 'EDIT_DISTRIBUTION') {
            // Combine create and edit because update on edit is to complex.
            // Instead of edit, delete first then recreate
            if ($action === 'EDIT_DISTRIBUTION') {
                $old_distribution = $details['old_distribution'];
                $new_distribution = $details['new_distribution'];
                AnnouncementDistribution::where('distribution_id', $old_distribution->id)->delete();
                $distribution = $new_distribution;
            } else {
                $distribution = $details['distribution'];
            }
            $media_id = $distribution->media_id;
            $media_name = Media::where('id', $media_id)->first()->name;
            // By default, the expected value of the column is not null
            $column_value = null;
            if ($media_name === 'Rotating Slide') {
                $column = 'rotating_slide';
                $column_value = false;
            } elseif ($media_name === 'Pengumuman Misa') {
                $column = 'mass_announcement';
            } elseif ($media_name === 'Flyer') {
                $column = 'flyer';
                $column_value = false;
            } elseif ($media_name === 'Bulletin Dombaku') {
                $column = 'bulletin';
            } elseif ($media_name === 'Website') {
                $column = 'website';
            } elseif ($media_name === 'Facebook') {
                $column = 'facebook';
            } elseif ($media_name === 'Instagram') {
                $column = 'instagram';
            }
            $routine_announcements = Announcement::whereBetween('date_time', 
                                                                [Carbon::parse($distribution->date_time)->format('Y-m-d H:i:s'),
                                                                 Carbon::parse($distribution->date_time)->addDays(35)->format('Y-m-d H:i:s')])
                                                 ->where($column, '!=', $column_value)
                                                 ->where('is_approved', true)->get();
            $not_routine_announcements = Announcement::whereBetween('date_time', 
                                                                    [Carbon::parse($distribution->date_time)->addDays(35)->format('Y-m-d H:i:s'),
                                                                     Carbon::parse($distribution->date_time)->addDays(70)->format('Y-m-d H:i:s')])
                                                     ->where($column, '!=', $column_value)
                                                     ->where('is_approved', true)->get();
            foreach ($routine_announcements as $announcement) {
                AnnouncementDistribution::create([
                    'announcement_id' => $announcement->id,
                    'distribution_id' => $distribution->id,
                    'revision_no' => $announcement->current_revision_no,
                ]);
            }
            foreach ($not_routine_announcements as $announcement) {
                AnnouncementDistribution::create([
                    'announcement_id' => $announcement->id,
                    'distribution_id' => $distribution->id,
                    'revision_no' => $announcement->current_revision_no,
                ]);
            }
        } elseif ($action === 'DELETE_DISTRIBUTION') {
            // Nothing to do
            // The deletion is handle by the database (onDelete cascade)
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
        if ($distribution_id === null) {
            $now = Carbon::now();
            $offline_media_ids = Media::where('is_online', false)
                                      ->pluck('id')
                                      ->toArray();
            $offline_distributions = Distribution::where('date_time', '>', $now)
                                                 ->whereIn('media_id', $offline_media_ids)
                                                 ->orderBy('date_time')
                                                 ->get();
            foreach ($offline_distributions as $distribution) {
                $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
                $distribution->deadline = Carbon::parse($distribution->deadline)->format('l, j F Y, g:i a');
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
            $online_media_ids = Media::where('is_online', true)
                                     ->pluck('id')
                                     ->toArray();
            $online_distributions = Distribution::whereIn('media_id', $online_media_ids)
                                                ->orderBy('description')
                                                ->get();
            return view('announcementdistribution.index', ['offline_distributions' => $offline_distributions,
                                                           'online_distributions' => $online_distributions]);
        } else {
            $distribution = Distribution::where('id', $distribution_id)->first();
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
            if (!$distribution->is_online) {
                $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                      ->where('is_rejected', false)
                                                                      ->whereNotNull('revision_no')
                                                                      ->get();         
            } else {
                $thirty_five_days_after = Carbon::now()->addDays(35)->format('Y-m-d H:i:s');
                $announcement_ids = $distribution->announcements()->where('date_time', '>', $now)
                                                               ->where('date_time', '<', $thirty_five_days_after)
                                                               ->where('is_approved', true)
                                                               ->pluck('announcement.id')->toArray();
                $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                      ->whereIn('announcement_id', $announcement_ids)
                                                                      ->whereNotNull('revision_no')
                                                                      ->get();
            }
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
                                                                  ->whereNotNull('revision_no')
                                                                  ->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)
                                    ->where('revision_no', $announcement_distribution->revision_no)
                                    ->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                $revision->reject_reason = $announcement_distribution->reject_reason;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                $revision->description = $revision->get_description_by_media_name($media_name);
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
            abort(403);
        }
        if ($distribution_id === null) {
            $ten_days_before = Carbon::now()->subDays(10);
            $offline_media_ids = Media::where('is_online', false)
                                      ->pluck('id')
                                      ->toArray();
            $offline_distributions = Distribution::where('date_time', '>', $ten_days_before)
                                                 ->whereIn('media_id', $offline_media_ids)
                                                 ->orderBy('date_time')
                                                 ->get();
            foreach ($offline_distributions as $distribution) {
                $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
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
            $online_media_ids = Media::where('is_online', true)
                                     ->pluck('id')
                                     ->toArray();
            $online_distributions = Distribution::whereIn('media_id', $online_media_ids)
                                                ->orderBy('description')
                                                ->get();
            return view('announcementdistribution.manage.distributions', ['offline_distributions' => $offline_distributions,
                                                                          'online_distributions' => $online_distributions]);
        } else {
            $distribution = Distribution::where('id', $distribution_id)->first();
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
            if (!$distribution->is_online) {
                $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                      ->where('is_rejected', false)
                                                                      ->whereNotNull('revision_no')   
                                                                      ->get();         
            } else {
                $thirty_five_days_after = Carbon::now()->addDays(35)->format('Y-m-d H:i:s');
                $announcement_ids = $distribution->announcements()->where('date_time', '>', $now)
                                                               ->where('date_time', '<', $thirty_five_days_after)
                                                               ->where('is_approved', true)
                                                               ->pluck('announcement.id')->toArray();
                $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                      ->whereIn('announcement_id', $announcement_ids)
                                                                      ->whereNotNull('revision_no')
                                                                      ->get();
            }     
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)
                                    ->where('revision_no', $announcement_distribution->revision_no)
                                    ->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                $revision->description = $revision->get_description_by_media_name($media_name);
                $revision->creator_name = $revision->announcement()->first()->creator()->first()->name;
                array_push($announcements, $revision);
            }
            $rejected_announcements = array();
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                  ->where('is_rejected', true)
                                                                  ->whereNotNull('revision_no')
                                                                  ->get();            
            foreach ($announcement_distributions as $announcement_distribution) {
                // Append revision instead of the announcement
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)
                                    ->where('revision_no', $announcement_distribution->revision_no)
                                    ->first();
                $revision->announcement_distribution_id = $announcement_distribution->id;
                $revision->reject_reason = $announcement_distribution->reject_reason;
                if ($revision->image_path !== null) {
                    $revision->image_path = Storage::url($revision->image_path);
                }
                $revision->description = $revision->get_description_by_media_name($media_name);
                $revision->creator_name = $revision->announcement()->first()->creator()->first()->name;
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
            abort(403);
        }
        // Invalid URL
        if ($announcement_distribution_id === null) {
            abort(404);
        }
        $announcement_distribution = AnnouncementDistribution::where('id', $announcement_distribution_id)->first();
        // Invalid URL
        if (!$announcement_distribution) {
            abort(404);
        }
        $revision_number = $announcement_distribution->announcement()->first()->current_revision_no;
        AnnouncementDistribution::where('id', $announcement_distribution_id)->update([
            'revision_no' => $revision_number,
        ]);
        return redirect('/announcementdistribution/manage/'.$announcement_distribution->distribution_id, 303)
               ->with('success_message', 'Pengumuman tersebut dalam distribusi ini telah diubah ke versi terbaru.');
    }
    
    /**
     * Reject the announcement distribution (e.g. because of quota)
     * 
     * @param Request $request
     * @return redirect
     */
    public function reject(Request $request) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        $announcement_distribution_id = $request->input('id');
        $reason = $request->input('reason');
        // Invalid URL
        if ($announcement_distribution_id === null) {
            abort(404);
        }
        $announcement_distribution = AnnouncementDistribution::where('id', $announcement_distribution_id)->first();
        // Invalid URL
        if (!$announcement_distribution) {
            abort(404);
        }
        $is_rejected = $announcement_distribution->is_rejected;
        AnnouncementDistribution::where('id', $announcement_distribution_id)->update([
            'is_rejected' => true,
            'reject_reason' => $reason,
        ]);
        $success_message = 'Pengumuman tersebut telah berhasil ditolak dalam distribusi ini.';
        return redirect('/announcementdistribution/manage/'.$announcement_distribution->distribution_id, 303)->with('success_message', $success_message);
    }
    
    /**
     * Accept the rejected announcement distribution (e.g. because of quota)
     * 
     * @param Request $request
     * @return redirect
     */
    public function accept(Request $request, $announcement_distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        // Invalid URL
        if ($announcement_distribution_id === null) {
            abort(404);
        }
        $announcement_distribution = AnnouncementDistribution::where('id', $announcement_distribution_id)->first();
        // Invalid URL
        if (!$announcement_distribution) {
            abort(404);
        }
        AnnouncementDistribution::where('id', $announcement_distribution_id)->update([
            'is_rejected' => false,
            'reject_reason' => null,
        ]);
        $success_message = 'Pengumuman yang sebelumnya ditolak tersebut telah dimasukkan kembali dalam distribusi ini.';
        return redirect('/announcementdistribution/manage/'.$announcement_distribution->distribution_id, 303)->with('success_message', $success_message);
    }
    
    /**
     * Display the manage announcement in distribution page
     * 
     * @param Request $request
     * @param $distribution_id
     * @return ZipFile
     */
    public function download(Request $request, $distribution_id = null) {
        // Normal user is not allowed to access this page
        $user = Auth::user();
        if (!$user->is_distributor && !$user->is_manager && !$user->is_admin) {
            abort(403);
        }
        if ($distribution_id === null) {
            $now = Carbon::now();
            $distributions = Distribution::where('deadline', '<', $now->subHours(24)->format('Y-m-d H:i:s'))
                                         ->where('date_time', '>', $now->subDays(10)->format('Y-m-d H:i:s'))
                                         ->get();
            foreach ($distributions as $distribution) {
                $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
            }
            return view('announcementdistribution.download.download', ['distributions' => $distributions,
                                                                       'warning_message' => nl2br("Pengumuman dalam distribusi hanya bisa diunduh 24 jam setelah batas akhir (deadline).")]);
        } else {
            $distribution = Distribution::where('id', $distribution_id)->first();
            if (!$distribution) {
                abort(404);
            }
            $now = Carbon::now();
            $allow_download_distributions = Distribution::where('deadline', '<', $now->subHours(24)->format('Y-m-d H:i:s'))
                                                        ->where('date_time', '>', $now->subDays(10)->format('Y-m-d H:i:s'))
                                                        ->pluck('id')->toArray();
            // Distributions that cannot be downloaded yet
            if (!in_array($distribution_id, $allow_download_distributions)) {
                abort(403);
            }
            $script = $distribution->description.' ('.Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a').')'."\r\n\r\n";
            $media_name = $distribution->media()->first()->name;
            $announcement_distributions = AnnouncementDistribution::where('distribution_id', $distribution_id)
                                                                 ->where('is_rejected', false)
                                                                 ->whereNotNull('revision_no')
                                                                 ->get();
            // Collect the announcements data
            $i = 0;
            $image_path_array = array();
            foreach ($announcement_distributions as $announcement_distribution) {
                $i++;
                $revision = Revision::where('announcement_id', $announcement_distribution->announcement_id)
                                    ->where('revision_no', $announcement_distribution->revision_no)
                                    ->first();
                $script .= $i.'. '.$revision->title."\r\n";
                $script .= $revision->get_description_by_media_name($media_name)."\r\n\r\n";
                if ($revision->image_path !== null) {
                    $image_path = storage_path('app/'.$revision->image_path);
                    array_push($image_path_array, $image_path);
                }
                
            }
            try {
                // Create a ZIP file
                $zip_filename = storage_path('app/public/announcement/announcements.zip');
                $zip = new ZipArchive();
                if ($zip->open($zip_filename, ZipArchive::CREATE)) {
                    // Put the files
                    $zip->addFromString("teks_pengumuman.txt", $script);
                    foreach ($image_path_array as $image_path) {
                        $zip->addFile($image_path, basename($image_path));
                    }
                    $zip->close();
                } else {
                    abort(500);
                }
                // Delete script file
                return response()->download($zip_filename)->deleteFileAfterSend(true);
            } catch (Exception $e) {
                abort(500);
            }
        }
    }
}
