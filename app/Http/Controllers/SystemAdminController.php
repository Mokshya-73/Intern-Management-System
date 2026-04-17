<?php

namespace App\Http\Controllers;

use App\Models\UserCoreData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SystemAdminController extends Controller
{
    public function loginForm()
    {
        return view('system_admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = UserCoreData::where('email', $request->email)->where('role_id', 1)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['admin_id' => $user->id]);
            return redirect()->route('system_admin.dashboard');
        }

        return back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function dashboard()
    {
        return view('system_admin.dashboard');
    }

    public function logout()
    {
        session()->forget('admin_id');
        return redirect()->route('system_admin.login');
    }
}
