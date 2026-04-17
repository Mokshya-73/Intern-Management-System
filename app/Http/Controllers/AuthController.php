<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCoreData;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'identity' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $user = UserCoreData::where('email', $request->identity)
    //         ->orWhere('reg_no', $request->identity)
    //         ->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return back()->withErrors(['error' => 'Invalid credentials']);
    //     }

    //     session([
    //         'user_id' => $user->id,
    //         'reg_no' => $user->reg_no,
    //         'role_id' => $user->role_id,
    //     ]);

    //     return $this->redirectBasedOnRole($user->role_id);
    // }
    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required',
            'password' => 'required',
        ]);

        // Try to find user by email or reg_no
        $user = UserCoreData::where('email', $request->identity)
            ->orWhere('reg_no', $request->identity)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Invalid credentials']);
        }

        // Log user in using Laravel Auth
        Auth::login($user);

        return $this->redirectBasedOnRole($user->role_id);
    }

    private function redirectBasedOnRole($roleId)
    {
        return match ($roleId) {
            1 => redirect()->route('intern.dashboard'),
            2 => redirect()->route('supervisor.dashboard'),
            3 => redirect()->route('hod.dashboard'),
            4 => redirect()->route('approver1.dashboard'),
            5 => redirect()->route('approver2.dashboard'),
            6 => redirect()->route('admin.dashboard'),
            default => abort(403),
        };
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login.form');
    }
}
