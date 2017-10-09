<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Distribution;
use App\Media;

class MediaController extends Controller
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
     * Display the list of media
     * 
     * @return Response
     */
    public function index(Request $request) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            abort(403);
        }
        $media =  Media::get();
        return view('media.index', ['media' => $media]);
    }
    
    public function create(Request $request) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            abort(403);
        }
        if ($request->isMethod('get')) {
            // Display new media form
            return view('media.create');
        } else {
            $name = $request->input('name');
            if ($request->input('is-online') === 'yes') {
                $is_online = true;
            } else {
                $is_online = false;
            }
            $media = Media::create([
                'name' => $name,
                'is_online' => $is_online,
            ]);
            // Automatically create distribution for online media
            if ($media->is_online) {
                Distribution::create([
                    'description' => $name,
                    'date_time' => null,
                    'deadline' => null,
                    'media_id' => $media->id,
                ]);
            }
            return redirect('/media', 303)->with('success_message', 'Media telah berhasil dibuat');
        }
    }
    
    public function edit(Request $request, $media_id = null) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            abort(403);
        }
        if ($request->isMethod('get')) {    
            // Invalid URL
            if ($media_id === null) {
                abort(404);
            }
            $media = Media::where('id', $media_id)->first();
            // Invalid URL
            if (!$media) {
                abort(404);
            }
            return view('media.edit', ['media' => $media]);
        } else {
            $id = $request->input('id');
            $name = $request->input('name');
            if ($request->input('is-online') === 'yes') {
                $is_online = true;
            } else {
                $is_online = false;
            }
            $old_media = Media::where('id', $id)->first();
            Media::where('id', $id)->update([
                'name' => $name,
                'is_online' => $is_online,
            ]);
            $new_media = Media::where('id', $id)->first();
            if ($old_media->is_online === $new_media->is_online) {
                // Online media that is not changed
                if ($new_media->is_online) {
                    $new_media->distributions()->update([
                        'description' => $name,
                    ]);
                }
            } else {
                if ($new_media->is_online) {
                    // Offline media becomes online media
                    $new_media->distributions()->delete();
                    // Automatically create distribution for online media
                    Distribution::create([
                        'description' => $name,
                        'media_id' => $new_media->id,
                    ]);
                } else {
                    // Online media becomes offline media
                    $new_media->distributions()->delete();
                }
            }
            return redirect('/media', 303)->with('success_message', 'Media telah berhasil diubah.');
        }
    }
    
    public function delete(Request $request, $media_id = null) {
        // Non-admin user cannot delete media
        $user = Auth::user();
        if (!$user->is_admin) {
            abort(403);
        }
        // Invalid URL
        if ($media_id === null) {
            abort(404);
        }
        $media = Media::where('id', $media_id)->first();
        // Invalid URL
        if (!$media) {
            abort(404);
        }
        $distribution = $media->distributions()->first();
        if ($media->is_online) {
            $distribution->delete();
        } else {
            if ($distribution) {
                return redirect('/media')->with('error_message', 'Anda tidak dapat menghapus media ini karena masih ada distribusi yang menggunakan media ini.');
            }
        }
        $media->delete();
        return redirect('/media', 303)->with('success_message', 'Media telah berhasil dihapus.');
    }
}
