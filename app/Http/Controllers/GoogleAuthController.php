<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\InternProfile;
use App\Models\UserCoreData;

class GoogleAuthController extends Controller
{
    // 🔀 Redirect to Google (login or connect)
    public function redirectToGoogle(Request $request)
    {
        $mode = $request->query('mode', 'login'); // login or connect
        $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return redirect($redirectUrl . '&state=' . $mode);
    }

    // ✅ Unified callback for login & connect
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $mode = request()->query('state', 'login'); // catch mode from query

            if ($mode === 'connect') {
                return $this->handleConnect($googleUser);
            }

            return $this->handleLogin($googleUser);
        } catch (\Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage());
            return redirect()->route('login.form')->with('error', 'Google authentication failed.');
        }
    }

    // 🔐 Handle Google login
    private function handleLogin($googleUser)
    {
        // Try to find the user by Google ID or email
        $user = UserCoreData::where('google_id', $googleUser->id)
            ->orWhere('google_email', $googleUser->email)
            ->first();

         // If user is found, but it's not the currently logged-in user (by ID)
        if ($user && $user->id !== Auth::id()) {
            return redirect()->route('login.form')->with('error', 'This Google account is already connected to another user.');
        }

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'No account linked to this Google account.');
        }

        Auth::login($user);
        switch ($user->role_id) {
            case 1:
                return redirect()->route('intern.dashboard');
            case 2:
                return redirect()->route('supervisor.dashboard');
            case 3:
                return redirect()->route('hod.dashboard');
            case 4:
                return redirect()->route('approver1.dashboard');
            case 5:
                return redirect()->route('approver2.dashboard');
            case 6:
                return redirect()->route('admin.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login.form')->with('error', 'Your role is not recognized.');
        }

    }

    // 🔗 Handle Google connect (from dashboard)
    private function handleConnect($googleUser)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $user->google_id = $googleUser->id;
        $user->google_email = $googleUser->email;
        $user->save();

        switch (auth()->user()->role_id) {
            case 1:
                return redirect()->route('intern.dashboard')->with('success', 'Google account connected!');
            case 2:
                return redirect()->route('supervisor.dashboard')->with('success', 'Google account connected!');
            case 3:
                return redirect()->route('hod.dashboard')->with('success', 'Google account connected!');
            case 4:
                return redirect()->route('approver1.dashboard')->with('success', 'Google account connected!');
            case 5:
                return redirect()->route('approver2.dashboard')->with('success', 'Google account connected!');
            case 6:
                return redirect()->route('admin.dashboard')->with('success', 'Google account connected!');
            default:
                return redirect()->route('login.form')->with('error', 'Unknown role.');
        }

    }


    // ❌ Disconnect Google account
public function disconnectGoogleAccount()
{
    $user = Auth::user();

    if ($user) {
        $user->google_id = null;
        $user->google_email = null;
        $user->save();

        switch ($user->role_id) {
            case 1:
                return redirect()->route('intern.dashboard')->with('success', 'Disconnected Google account.');
            case 2:
                return redirect()->route('supervisor.dashboard')->with('success', 'Disconnected Google account.');
            case 3:
                return redirect()->route('hod.dashboard')->with('success', 'Disconnected Google account.');
            case 4:
                return redirect()->route('approver1.dashboard')->with('success', 'Disconnected Google account.');
            case 5:
                return redirect()->route('approver2.dashboard')->with('success', 'Disconnected Google account.');
            case 6:
                return redirect()->route('admin.dashboard')->with('success', 'Disconnected Google account.');
            default:
                Auth::logout();
                return redirect()->route('login.form')->with('error', 'Unknown role. Please login again.');
        }
    }

    return redirect()->route('login.form')->with('error', 'User not authenticated.');
}


}
