<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
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
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        $now = Carbon::now();
        $distributions = Distribution::where('date_time', '>', $now)->get();
        foreach ($distributions as $distribution) {
            $distribution->date_time = Carbon::parse($distribution->date_time)->format('l, j F Y, g:i a');
            $distribution->deadline = Carbon::parse($distribution->deadline)->format('l, j F Y, g:i a');
            $distribution->media_name = $distribution->media()->first()->name;
            $deadline = Carbon::parse($distribution->deadline);
            
            $now_to_deadline_diff = $now->diffInSeconds($deadline, false);
            if ($now_to_deadline_diff < 0) {
                $distribution->status = 'FINAL';
            } else if (0 < $now_to_deadline_diff && $now_to_deadline_diff < 24 * 3600) {
                $distribution->status = 'MENDEKATI BATAS AKHIR (DEADLINE)';
            } else {
                $distribution->status = 'MENERIMA PENGUMUMAN';
            }
        }      
        return view('distribution.index', ['distributions' => $distributions]);
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
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        if ($request->isMethod('get')) {
            $media = Media::get();
            return view('distribution.create', ['media' => $media]);
        } else {
            $description = $request->input('description');
            $date_time = $request->input('date-time');
            $deadline_time = $request->input('deadline');
            $media_id = $request->input('media');
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
                'action' => 'NEW_DISTRIBUTION',
                'id' => $distribution->id,
            );
            // Must call the function in another controller non-statically
            // Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
            return redirect('/distribution')->with('success_message', 'Distribusi telah berhasil dibuat.');
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
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        if ($request->isMethod('get')) {
            // Invalid URL
            if ($distribution_id === null) {
                return redirect('/distribution')->with('error_message', 'Link yang Anda masukkan salah.');
            }
            $distribution = Distribution::where('id', $distribution_id)->first();
            // Invalid URL
            if (!$distribution) {
                return redirect('/distribution')->with('error_message', 'Link yang Anda masukkan salah.');
            }
            $media = Media::get();
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
            Distribution::where('id', $id)->update([
                'description' => $description,
                'date_time' => $date_time,
                'deadline' => $deadline_time,
                'media_id' => $media_id,
            ]);
            $update_announcement_distribution_details = array(
                'action' => 'EDIT_DISTRIBUTION',
                'id' => $id,
            );
            // Must call the function in another controller non-statically
            // Reference: https://stackoverflow.com/a/19694064
            (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
            return redirect('/distribution')->with('success_message', 'Distribusi telah berhasil diubah.');
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
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        // Invalid URL
        if ($distribution_id === null) {
            return redirect('/distribution')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $distribution = Distribution::where('id', $distribution_id)->first();
        // Invalid URL
        if (!$distribution) {
            return redirect('/distribution')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        
        $update_announcement_distribution_details = array(
            'action' => 'DELETE_DISTRIBUTION',
            'id' => $distribution->id,
        );
        // Must call the function in another controller non-statically
        // Reference: https://stackoverflow.com/a/19694064
        (new AnnouncementDistributionController)->update($update_announcement_distribution_details);
        $distribution->delete();
        return redirect('/distribution')->with('success_message', 'Distribusi Anda telah berhasil dihapus.');
    }
}
