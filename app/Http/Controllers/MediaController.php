<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Media;

class MediaController extends Controller
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
     * Display the list of media
     * 
     * @return Response
     */
    public function index(Request $request) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        $media =  Media::get();
        return view('media.index', ['media' => $media]);
    }
    
    public function create(Request $request) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
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
            Media::create([
                'name' => $name,
                'is_online' => $is_online,
            ]);
            return redirect('/media')->with('success_message', 'Media telah berhasil dibuat');
        }
    }
    
    public function edit(Request $request, $media_id = null) {
        // Non-admin user cannot edit media
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        if ($request->isMethod('get')) {    
            // Invalid URL
            if ($media_id === null) {
                return redirect('/media')->with('error_message', 'Link yang Anda masukkan salah.');
            }
            $media = Media::where('id', $media_id)->first();
            // Invalid URL
            if (!$media) {
                return redirect('/media')->with('error_message', 'Link yang Anda masukkan salah.');
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
            Media::where('id', $id)->update([
                'name' => $name,
                'is_online' => $is_online,
            ]);
            return redirect('/media')->with('success_message', 'Media telah berhasil diubah.');
        }
    }
    
    public function delete(Request $request, $media_id = null) {
        // Non-admin user cannot delete media
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect('/')->with('error_message', 'Anda tidak diizinkan mengakses halaman ini.');
        }
        // Invalid URL
        if ($media_id === null) {
            return redirect('/media')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $media = Media::where('id', $media_id)->first();
        // Invalid URL
        if (!$media) {
            return redirect('/media')->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $distribution = $media->distributions()->first();
        if ($distribution) {
            return redirect('/media')->with('error_message', 'Anda tidak dapat menghapu media ini karena masih ada distribusi yang menggunakan media ini.');
        }
        $media->delete();
        return redirect('/media')->with('success_message', 'Media telah berhasil dihapus.');
    }
}
