<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\User;
use App\Mail\ActivateAccount;
use App\Mail\PasswordChanged;
use App\Mail\ResetPassword;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
    }
    
    /**
     * Handle an login attempt
     * 
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) {
        if ($request->isMethod('get')) {
            // If user is authenticated
            if (Auth::check()) {
                return redirect('/');
            }
            // Display the login page
            return view('user.login');
        } else {
            // Attempt to login
            $email = $request->input('email');
            $email = strtolower($email);
            $password = $request->input('password');
            $remember = $request->input('remember');
            $credentials = array('email' => $email, 
                                 'password' => $password, 
                                 'is_active' => true);
            if (Auth::attempt($credentials, $remember)) {
                // The user is active, not suspended, and exists
                return redirect('/')->with('success_message', 'Login berhasil.');
            } elseif (User::where('email', $email)->where('is_active', false)->first()) {
                // The user is not active
                return view('user.login', ['warning_message' => 'Akun Anda belum aktif. Mohon cek email Anda.']);
            } elseif (!User::where('email', $email)->first()) {
                // The user is not registered
                return view('user.login', ['error_message' => 'Akun Anda tidak terdaftar.']);
            } else {
                return view('user.login', ['error_message' => 'Email atau password Anda tidak dapat dikenali.']);
            }
        }
    }
    
    /**
     * Handle an logout attempt
     * 
     * @param Request $request
     * @return redirect
     */
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/')->with('success_info', 'Logout berhasil.');
    }
    
    /**
     * Handle a register attempt
     * 
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) {
        $name = $request->input('name');
        $organization_name = $request->input('organization-name');
        $email = $request->input('email');
        $email = strtolower($email);
        $password = $request->input('password');
        // Encrypt the password
        $password = Hash::make($password);
        // Encode the email (for activation purpose)
        $email_token = base64_encode($email);
        $credentials = array('name' => $name, 
                             'organization_name' => $organization_name, 
                             'email' => $email,
                             'password' => $password,
                             'email_token' => $email_token);
        if (User::where('email', $email)->first()) {
            return view('user.login', ['error_message' => 'Email telah terdaftar.']);
        } else {
            $user = User::create($credentials);
            // By default, the first user in the database is assigned as the staff and the admin.
            if (User::count() == 1) {
                $user->is_admin = true;
                $user->is_manager = true;
                $user->is_distributor = true;
                $user->save();
            }
            Mail::to($user)->send(new ActivateAccount($user));
            return redirect('/login')->with('success_message', 'Akun Anda sudah berhasil didaftarkan. Silakan cek email Anda untuk aktivasi.');
        }
    }
    
    /**
     * Handle an activation attempt
     * 
     * @param Request $request
     * @param string $token
     * @return redirect
     */
    public function activate(Request $request, $token) {
        $user = User::where('email_token', $token)->first();
        if (!$user) {
            return redirect('/login', 303)->with('error_message', 'Tautan yang Anda masukkan tidak valid.');
        }
        $user->email_token = null;
        $user->is_active = true;
        $user->save();
        return redirect('/login', 303)->with('success_message', 'Akun Anda sudah berhasil diaktivasi. Silakan login.');
    }
    
    /**
     * Handle a update profile attempt
     * 
     * @param Request $request
     * @return redirect
     */
    public function update_profile(Request $request) {
        if ($request->isMethod('get')) {
            $user = Auth::user();
            return view('user.updateprofile', ['user' => $user]);
        } else {
            # Get the request data
            $name = $request->input('name');
            $organization_name = $request->input('organization-name');
            # Proceed to update the profile
            $user = Auth::User();
            $user->update(['name' => $name,
                           'organization_name' => $organization_name]);
            return redirect('/', 303)->with('success_message', 'Profil Anda telah berhasil diubah.');
        }
    }
    
    /**
     * Handle a change password attempt
     * 
     * @param Request $request
     * @return redirect
     */
    public function change_password(Request $request) {
        if ($request->isMethod('get')) {
            return view('user.changepassword');
        } else {
            // Get the request data
            $old_password = $request->input('old-password');
            $new_password = $request->input('new-password');
            // Proceed to update the profile
            $user = Auth::User();
            // Compare the old password from the user to the password in the DB 
            if (!Hash::check($old_password, $user->password)) {
                return view('user.changepassword', ['error_message' => 'Password lama yang Anda masukkan salah.']);
            } else {
                $user_id = Auth::id();
                $user = User::where('id', $user_id)->first();
                // Encrypt the password
                $new_password = Hash::make($new_password);
                // Update the password
                $user->update(['password' => $new_password]);
                // Send email to user for acknowledgement
                Mail::to($user)->send(new PasswordChanged($user));
                // Logout
                Auth::logout();
                return redirect('/login', 303)->with('success_message', 'Password Anda telah berhasil diganti. Silakan login ulang.');
            }
            
        }
    }
    
    /**
     * Handle a request to reset password (forget password)
     * 
     * @param Request $request
     * @return redirect
     */
    public function forget_password(Request $request) {
        if ($request->isMethod('get')) {
            return view('user.forgetpassword');
        } else {
            $email = $request->input('email');
            $email = strtolower($email);
            // Encode the email (for reset purpose)
            $email_token = base64_encode($email);
            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect('/login', 303)->with('error_message', 'Email yang Anda masukkan tidak terdaftar.');
            } 
            $user->update(['email_token' => $email_token]);
            // Send email to user for follow up
            Mail::to($user)->send(new ResetPassword($user));
            return redirect('/login', 303)->with('success_message', 'Permintaan untuk mengatur ulang (reset) password Anda telah berhasil diproses. Silakan cek email Anda untuk proses lebih lanjut.');
        }
    }
    
    /**
     * Handle a reset password attempt
     * 
     * @param Request $request
     * @param $token
     * @return Response/redirect
     */
    public function reset_password(Request $request, $token = null) {
        if ($request->isMethod('get')) {
            $user = User::where('email_token', $token)->first();
            if (!$user) {
                return redirect('/login', 303)->with('error_message', 'Tautan yang Anda masukkan tidak valid.');
            }
            return view('user.resetpassword', ['user' => $user]);
        } else {
            $email = $request->input('email');
            $email = strtolower($email);
            $password = $request->input('password');
            $user = User::where('email', $email)->first();
            // Encrypt the password
            $password = Hash::make($password);
            // Update the password
            $user->update(['password' => $password,
                           'email_token' => null]);
            Auth::logout();
            // Send email to user for acknowledgement
            Mail::to($user)->send(new PasswordChanged($user));
            return redirect('/login', 303)->with('success_message', 'Password Anda telah berhasil diatur ulang (reset). Silakan login ulang.');
        }
    }
    
    /**
     * Display a account management page
     * 
     * @param Request $request
     * @return Response/redirect
     */
    public function manage(Request $request) {
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        $accounts = User::get();
        $user = Auth::user();
        return view('account.manage', ['accounts' => $accounts, 'user' => $user]);
    }
    
    /**
     * Set the role of a user a account management page
     * 
     * @param Request $request
     * @return Response/redirect
     */
    public function set(Request $request, $role = null, $user_id = null) {
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        $roles = array('distributor', 'manager', 'admin');
        if ($role === null || $user_id === null || !in_array($role, $roles)) {
            return redirect('/accountmanagement/', 303)->with('error_message', 'Link yang Anda masukkan salah.');
        }
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return redirect('/accountmanagement/', 303)->with('error_message', 'Link yang Anda masukkan salah.');
        }
        if ($role === 'distributor') {
            $previous_condition = $user->is_distributor;
            User::where('id', $user_id)->update([
                'is_distributor' => !$previous_condition,
            ]);
        } elseif ($role === 'manager') {
            $previous_condition = $user->is_manager;
            User::where('id', $user_id)->update([
                'is_manager' => !$previous_condition,
            ]);
        } else {
            // Admin
            $previous_condition = $user->is_admin;
            User::where('id', $user_id)->update([
                'is_admin' => !$previous_condition,
            ]);
        }
        if ($previous_condition == true) {
            $action = 'diturunkan dari';
        } else {
            $action = 'dinaikkan ke';
        }
        $success_message = $user->name.' telah berhasil '.$action.' '.$role.'.';
        return redirect('/accountmanagement/', 303)->with('success_message', $success_message);
    }
    
}
