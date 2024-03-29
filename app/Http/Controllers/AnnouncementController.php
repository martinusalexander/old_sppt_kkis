<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Announcement;
use App\Revision;
use App\Http\Controllers\AnnouncementDistributionController;
use App\Mail\ApproveAnnouncement;

class AnnouncementController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Display the list of announcements
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        $user = Auth::user();
        $time_now = Carbon::now()->format('Y-m-d H:i:s');
        if (!($user->is_admin || $user->is_manager)) {
            // Only display announcements created by the user
            $present_announcements = $user->announcements_created()->where('date_time', '>', $time_now)->orderBy('date_time')->get();
        } else {
            // Display all announcements
            $present_announcements = Announcement::where('date_time', '>', $time_now)->orderBy('date_time')->get();
        }
        return view('announcement.index', ['present_announcements' => $present_announcements]);
    }
    
    /**
     * Handle announcement creation attempt
     * 
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        if ($request->isMethod('get')) {
            // Display new announcement form
            return view('announcement.create');
        } else {
            // Save the new announcement
            $title = $request->input('title');
            $description = $request->input('description');
            $date_time = $request->input('date-time');
            $date_time = Carbon::parse($date_time)->format('Y-m-d H:i:s');
            if ($request->hasFile('image-path')) {
                $image = $request->file('image-path');
                $image_path = Storage::put('public/images', $image, 'public');
            } else {
                $image_path = null;
            }
            if ($request->input('is-routine') === 'yes') {
                $is_routine = true;
            } else {
                $is_routine = false;
            }
            if ($request->input('show-in-rotating-slide') === 'on') {
                $rotating_slide = true;                
            } else {
                $rotating_slide = false;
            }
            if ($request->input('show-in-mass-announcement')) {
                $mass_announcement = $request->input('mass-announcement');                
            } else {
                $mass_announcement = null;
            }
            if ($request->input('show-in-flyer') === 'on') {
                $flyer = true;
            } else {
                $flyer = false;
            }
            if ($request->input('show-in-bulletin')) {
                $bulletin = $request->input('bulletin');                
            } else {
                $bulletin = null;
            }
            if ($request->input('show-in-website')) {
                    $website = $request->input('website');                
                } else {
                    $website = null;
                }
            if ($request->input('show-in-facebook')) {
                $facebook = $request->input('facebook');                
            } else {
                $facebook = null;
            }
            if ($request->input('show-in-instagram')) {
                $instagram = $request->input('instagram');                
            } else {
                $instagram = null;
            }
            $announcement = Announcement::create([
                'title' => $title,
                'description' => $description,
                'date_time' => $date_time,
                'is_routine' => $is_routine,
                'image_path' => $image_path,
                'rotating_slide' => $rotating_slide,
                'mass_announcement' => $mass_announcement,
                'flyer' => $flyer,
                'bulletin' => $bulletin,
                'website' => $website,
                'facebook' => $facebook,
                'instagram' => $instagram,
                'creator_id' => Auth::id(),
            ]);
            // The original announcement is set to be revision 0
            $announcement_id = $announcement->id;
            Revision::create([
                'announcement_id' => $announcement_id,
                'revision_no' => 0,
                'title' => $title,
                'description' => $description,
                'date_time' => $date_time,
                'is_routine' => $is_routine,
                'image_path' => $image_path,
                'rotating_slide' => $rotating_slide,
                'mass_announcement' => $mass_announcement,
                'flyer' => $flyer,
                'bulletin' => $bulletin,
                'website' => $website,
                'facebook' => $facebook,
                'instagram' => $instagram,
                'submitter_id' => Auth::id(),
            ]);
            // Send email to admin and manager, asking for approval
            $admins_and_managers = User::where('is_admin', true)
                                       ->orWhere('is_manager', true)
                                       ->get();
            foreach ($admins_and_managers as $user) {
                Mail::to($user)->send(new ApproveAnnouncement($user));
            }
            return redirect('/announcement/', 303)->with('success_message', 'Pengumuman Anda telah berhasil dibuat.');
        }
    }
    
    /**
     * Handle announcement edit attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * @return Response
     */
    public function edit(Request $request, $announcement_id = null) {
        if ($request->isMethod("get")) {
            // Display the edit announcement form
            // Invalid URL
            if ($announcement_id === null) {
                abort(404);
            }
            $announcement = Announcement::where('id', $announcement_id)->first();
            // Invalid URL
            if (!$announcement) {
                abort(404);
            }
            // Non-admin and non-manager user cannot change other's annnouncement
            $user = Auth::user();
            if (!$user->is_admin && !$user->is_manager && $announcement->creator_id != Auth::id()) {
                abort(403);
            }
            // Get image URL
            if ($announcement->image_path !== null) {
                $announcement->image_path = Storage::url($announcement->image_path);
            }
            // Convert date to HTML input format
            $date_time = Carbon::parse($announcement->date_time)->format('m/d/Y g:i A');
            $announcement->date_time = $date_time;
            return view('announcement.edit', ['announcement' => $announcement]);
        } else {
            // Edit the announcement and create revision
            $id = $request->input('id');
            $title = $request->input('title');
            $description = $request->input('description');
            $date_time = $request->input('date-time');
            $date_time = Carbon::parse($date_time)->format('Y-m-d H:i:s');
            if ($request->input('image') === 'keep') {
                $image_path = Announcement::where('id', $id)->first()->image_path;
            } elseif ($request->input('image') === 'change') {
                if ($request->hasFile('image-path')) {
                    $image = $request->file('image-path');
                    $image_path = Storage::put('public/images', $image, 'public');
                } else {
                    $image_path = null;
                }
            } else {
                $image_path = null;
            }
            if ($request->input('is-routine') === 'yes') {
                $is_routine = true;
            } else {
                $is_routine = false;
            }
            if ($request->input('show-in-rotating-slide') === 'on') {
                $rotating_slide = true;                
            } else {
                $rotating_slide = false;
            }
            if ($request->input('show-in-mass-announcement')) {
                $mass_announcement = $request->input('mass-announcement');                
            } else {
                $mass_announcement = null;
            }
            if ($request->input('show-in-flyer') === 'on') {
                $flyer = true;
            } else {
                $flyer = false;
            }
            if ($request->input('show-in-bulletin')) {
                $bulletin = $request->input('bulletin');                
            } else {
                $bulletin = null;
            }
            if ($request->input('show-in-website')) {
                $website = $request->input('website');                
            } else {
                $website = null;
            }
            if ($request->input('show-in-facebook')) {
                $facebook = $request->input('facebook');                
            } else {
                $facebook = null;
            }
            if ($request->input('show-in-instagram')) {
                $instagram = $request->input('instagram');                
            } else {
                $instagram = null;
            }
            // Update the announcement
            $revision_number = Revision::where('announcement_id', $id)->max('revision_no') + 1;
            Announcement::where('id', $id)->update([
                'current_revision_no' => $revision_number,
                'title' => $title,
                'description' => $description,
                'date_time' => $date_time,
                'is_routine' => $is_routine,
                'image_path' => $image_path,
                'rotating_slide' => $rotating_slide,
                'mass_announcement' => $mass_announcement,
                'flyer' => $flyer,
                'bulletin' => $bulletin,
                'website' => $website,
                'facebook' => $facebook,
                'instagram' => $instagram,
                'last_editor_id' => Auth::id(),
                'is_approved' => false,
                'approver_id' => null,
            ]);
            $announcement = Announcement::where('id', $id)->first();
            // Create revision to the announcement
            Revision::create([
                'announcement_id' => $id,
                'revision_no' => $revision_number,
                'title' => $title,
                'description' => $description,
                'date_time' => $date_time,
                'is_routine' => $is_routine,
                'image_path' => $image_path,
                'rotating_slide' => $rotating_slide,
                'mass_announcement' => $mass_announcement,
                'flyer' => $flyer,
                'bulletin' => $bulletin,
                'website' => $website,
                'facebook' => $facebook,
                'instagram' => $instagram,
                'submitter_id' => Auth::id(),
            ]);
            $update_announcement_distribution_details = array(
                'action' => 'EDIT_ANNOUNCEMENT',
                'announcement' => $announcement,
            );
            // Must call the function in another controller non-statically
            //Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
            // Send email to admin and manager, asking for approval
            $admins_and_managers = User::where('is_admin', true)
                                       ->orWhere('is_manager', true)
                                       ->get();
            foreach ($admins_and_managers as $user) {
                Mail::to($user)->send(new ApproveAnnouncement($user));
            }
            return redirect('/announcement/', 303)->with('success_message', 'Pengumuman Anda telah berhasil diubah.');
        }
    }
    
    /**
     * Handle announcement view attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * @return Response
     */
    public function view(Request $request, $announcement_id = null) {
        // Display the view announcement page
        // Invalid URL
        if ($announcement_id === null) {
            abort(404);
        }
        $announcement = Announcement::where('id', $announcement_id)->first();
        // Invalid URL
        if (!$announcement) {
            abort(404);
        }
        // Get image URL
        if ($announcement->image_path !== null) {
            $announcement->image_path = Storage::url($announcement->image_path);
        } 
        // Convert date to HTML input format
        $date_time = Carbon::parse($announcement->date_time)->format('l, j F Y, g:i a');
        $announcement->date_time = $date_time;
        return view('announcement.view', ['announcement' => $announcement]);
    }
    
    /**
     * Handle announcement deletion attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * /@return Response
     */
    public function delete(Request $request, $announcement_id = null) {
        // Invalid URL
        if ($announcement_id === null) {
            abort(404);
        }
        $announcement = Announcement::where('id', $announcement_id)->first();
        // Invalid URL
        if (!$announcement) {
            abort(404);
        }
        // Non-admin and non-manager user cannot delete other's annnouncement
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager && $announcement->creator_id != Auth::id()) {
            abort(403);
        }
        $revisions = $announcement->revisions()->get();
        // Delete each image that belongs to the announcement
        foreach ($revisions as $revision) {
            $image_path = $revision->image_path;
            if (Storage::disk('local')->has($image_path)) {
                Storage::delete($image_path);
            }
        }
        if ($announcement->is_approved) {
            $update_announcement_distribution_details = array(
                'action' => 'DELETE_ANNOUNCEMENT',
                'announcement' => $announcement,
            );
            // Must call the function in another controller non-statically
            //Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
        }
        
        $announcement->delete();
        return redirect('/announcement/', 303)->with('success_message', 'Pengumuman Anda telah berhasil dihapus.');
    }
    
    /**
     * Handle announcement display before approval attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * @return Response
     */
    public function approve_view(Request $request, $announcement_id = null) {
        // Non-admin and non-manager user cannot go into this page
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager) {
            abort(403);
        }
        // Invalid URL
        if ($announcement_id === null) {
            abort(404);
        }
        $announcement = Announcement::where('id', $announcement_id)->first();
        // Invalid URL
        if (!$announcement) {
            abort(404);
        }
        // Get image URL
        if ($announcement->image_path !== null) {
            $announcement->image_path = Storage::url($announcement->image_path);
        } 
        // Convert date to HTML input format
        $date_time = Carbon::parse($announcement->date_time)->format('l, j F Y, g:i a');
        $announcement->date_time = $date_time;
        $announcement->creator_name = $announcement->creator()->first()->name;
        return view('announcement.approve.view', ['warning_message' => nl2br("Anda disarankan untuk mengevaluasi pengumuman ini dengan seksama."
                                                                    ."\n1. Pastikan informasi dalam pengumuman ini benar."
                                                                    ."\n2. Pastikan pengumuman yang tertera di sistem ini sama dengan yang akan ditampilkan."
                                                                    ."\n3. Jika diperlukan, ubahlah pengumuman ini sebelum menyetujuinya.", false),
                                                  'announcement' => $announcement]);
    }
    
    /**
     * Display the list of announcements to be approved
     * 
     * @param Request $request
     * @return Response
     */
    public function approve_index(Request $request) {
        // Non-admin and non-manager user cannot go into this page
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager) {
            abort(403);
        }
        $time_now = Carbon::now()->format('Y-m-d H:i:s');
        // Display all announcements
        $present_announcements = Announcement::where('date_time', '>', $time_now)->orderBy('date_time')->get();
        foreach ($present_announcements as $announcement) {
            $announcement->creator_name = $announcement->creator()->first()->name;
            if ($announcement->approver_id) {
                $announcement->approver_name = $announcement->approver()->first()->name;
            } else {
                $announcement->approver_name = '-';
            }
        }
        return view('announcement.approve.index', ['present_announcements' => $present_announcements]);
    }
    
    /**
     * Handle announcement approval (without revision) attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * @return Response
     */
    public function approve_confirm(Request $request, $announcement_id = null) {
        // Non-admin and non-manager user cannot go into this page
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager) {
            abort(403);
        }
        // Invalid URL
        if ($announcement_id === null) {
            abort(404);
        }
        $announcement = Announcement::where('id', $announcement_id)->first();
        // Invalid URL
        if (!$announcement) {
            abort(404);
        }
        Announcement::where('id', $announcement_id)->update([
                            'is_approved' => true,
                            'approver_id' => Auth::id()]);
        $update_announcement_distribution_details = array(
            'action' => 'APPROVE_ANNOUNCEMENT',
            'announcement' => $announcement,
        );
        // Must call the function in another controller non-statically
        //Reference: https://stackoverflow.com/a/19694064
        (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
        return redirect('/announcement/approve', 303)->with('success_message', 'Pengumuman telah berhasil disetujui.');
    }
    
    /**
     * Handle announcement edit before approval attempt
     * 
     * @param Request $request
     * @param $announcement_id
     * @return Response
     */
    public function approve_edit(Request $request, $announcement_id = null) {
        // Non-admin and non-manager user cannot go into this page
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager) {
            abort(403);
        }
        // Invalid URL
        if ($announcement_id === null) {
            abort(404);
        }
        $announcement = Announcement::where('id', $announcement_id)->first();
        $announcement->image_path = Storage::url($announcement->image_path);
        $announcement->date_time = Carbon::parse($announcement->date_time)->format('m/d/Y g:i A');
        // Invalid URL
        if (!$announcement) {
            abort(404);
        }
        return view('announcement.approve.edit', ['announcement' => $announcement]);
    }
    
    /**
     * Handle announcement approval (with revision) attempt
     * 
     * @param Request $request
     * @return Response
     */
    public function approve_confirm_edit(Request $request) {
        // Non-admin and non-manager user cannot go into this page
        $user = Auth::user();
        if (!$user->is_admin && !$user->is_manager) {
            abort(403);
        }
        // Edit the announcement and create revision
        $id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $date_time = $request->input('date-time');
        $date_time = Carbon::parse($date_time)->format('Y-m-d H:i:s');
        if ($request->input('image') === 'keep') {
            $image_path = Announcement::where('id', $id)->first()->image_path;
        } elseif ($request->input('image') === 'change') {
            if ($request->hasFile('image-path')) {
                $image = $request->file('image-path');
                $image_path = Storage::put('public/images', $image, 'public');
            } else {
                $image_path = null;
            }
        } else {
            $image_path = null;
        }
        if ($request->input('is-routine') === 'yes') {
            $is_routine = true;
        } else {
            $is_routine = false;
        }
        if ($request->input('show-in-rotating-slide') === 'on') {
            $rotating_slide = true;                
        } else {
            $rotating_slide = false;
        }
        if ($request->input('show-in-mass-announcement')) {
            $mass_announcement = $request->input('mass-announcement');                
        } else {
            $mass_announcement = null;
        }
        if ($request->input('show-in-flyer') === 'on') {
            $flyer = true;
        } else {
            $flyer = false;
        }
        if ($request->input('show-in-bulletin')) {
            $bulletin = $request->input('bulletin');                
        } else {
            $bulletin = null;
        }
        if ($request->input('show-in-website')) {
            $website = $request->input('website');                
        } else {
            $website = null;
        }
        if ($request->input('show-in-facebook')) {
            $facebook = $request->input('facebook');                
        } else {
            $facebook = null;
        }
        if ($request->input('show-in-instagram')) {
            $instagram = $request->input('instagram');                
        } else {
            $instagram = null;
        }
        // Update the announcement
        $revision_number = Revision::where('announcement_id', $id)->max('revision_no') + 1;
        Announcement::where('id', $id)->update([
            'current_revision_no' => $revision_number,
            'title' => $title,
            'description' => $description,
            'date_time' => $date_time,
            'is_routine' => $is_routine,
            'image_path' => $image_path,
            'rotating_slide' => $rotating_slide,
            'mass_announcement' => $mass_announcement,
            'flyer' => $flyer,
            'bulletin' => $bulletin,
            'website' => $website,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'last_editor_id' => Auth::id(),
            'is_approved' => true,
            'approver_id' => Auth::id()
        ]);
        $announcement = Announcement::where('id', $id)->first();
        // Create revision to the announcement
        Revision::create([
            'announcement_id' => $id,
            'revision_no' => $revision_number,
            'title' => $title,
            'description' => $description,
            'date_time' => $date_time,
            'is_routine' => $is_routine,
            'image_path' => $image_path,
            'rotating_slide' => $rotating_slide,
            'mass_announcement' => $mass_announcement,
            'flyer' => $flyer,
            'bulletin' => $bulletin,
            'website' => $website,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'submitter_id' => Auth::id(),
        ]);
        $update_announcement_distribution_details = array(
            'action' => 'APPROVE_ANNOUNCEMENT',
            'announcement' => $announcement,
        );
        // Must call the function in another controller non-statically
        //Reference: https://stackoverflow.com/a/19694064
        (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
        return redirect('/announcement/approve', 303)->with('success_message', 'Pengumuman telah berhasil diubah dan disetujui.');
    }
}
