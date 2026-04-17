<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\InternProfile;

class ResetPasswordController extends Controller
{
    // Show the reset password form
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                // ✅ Track previous passwords
                $intern = InternProfile::where('email', $user->email)->first();
                if ($intern) {
                    $oldPasswords = json_decode($intern->previous_passwords ?? '[]', true);
                    $oldPasswords[] = $intern->password;

                    $intern->previous_passwords = json_encode($oldPasswords);
                    $intern->password = Hash::make($password);
                    $intern->save();
                }

                // ✅ Update core password
                $user->password = Hash::make($password);
                $user->save();

                // ✅ Store raw password and email temporarily in session
                session()->flash('reset_success', true);
                session()->flash('password_plain', $password);
                session()->put('email', $request->email); // needed for NIC check later

                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('password.reset', ['token' => $request->token, 'email' => $request->email])
            : back()->withErrors(['email' => __($status)]);
    }

}
