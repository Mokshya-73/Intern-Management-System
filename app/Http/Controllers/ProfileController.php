<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserCoreData;
use App\Models\InternProfile;
use App\Models\Supervisor;
use App\Models\Hod;
use App\Models\Approver1;
use App\Models\Approver2;

class ProfileController extends Controller
{
    // 🌐 INTERN
    public function showIntern()
    {
        $user = Auth::user();
        $intern = InternProfile::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('interns.profile.show', compact('intern', 'core'));
    }

    public function editIntern()
    {
        $user = Auth::user();
        $intern = InternProfile::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('interns.profile.edit', compact('intern', 'core'));
    }

    public function updateIntern(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'certificate_name' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $intern = InternProfile::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();

        $intern->name = $request->name;
        $intern->certificate_name = $request->certificate_name;
        $intern->mobile = $request->mobile;
        $intern->description = $request->description;

        if ($request->filled('password')) {
            $intern->password = Hash::make($request->password);
            $core->password = Hash::make($request->password);
        }

        $intern->save();
        $core->save();

        return redirect()->route('interns.profile.show')->with('success', 'Profile updated successfully.');
    }

    // 🧑‍🏫 SUPERVISOR
    public function showSupervisor() { 
        $user = Auth::user();
        $supervisor = Supervisor::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('supervisor.profile.show', compact('supervisor', 'core'));
    }
    public function editSupervisor() {
        $user = Auth::user();
        $supervisor = Supervisor::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('supervisor.profile.edit', compact('supervisor', 'core'));
    }
    public function updateSupervisor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $supervisor = Supervisor::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();

        $supervisor->name = $request->name;
        $supervisor->mobile = $request->mobile;
        $supervisor->designation = $request->designation;
        $supervisor->description = $request->description;

        if ($request->filled('password')) {
            $supervisor->password = Hash::make($request->password);
            $core->password = Hash::make($request->password);
        }

        $supervisor->save();
        $core->save();

        return redirect()->route('supervisor.profile.show')->with('success', 'Profile updated successfully.');
    }

    // 🧑‍🎓 HOD
    public function showHOD() {
        $user = Auth::user();
        $hod = HOD::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('hod.profile.show', compact('hod', 'core'));
    }
    public function editHOD() {
        $user = Auth::user();
        $hod = HOD::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('hod.profile.edit', compact('hod', 'core'));
    }
    public function updateHOD(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $hod = HOD::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();

        $hod->name = $request->name;
        $hod->description = $request->description;

        if ($request->filled('password')) {
            $hod->password = Hash::make($request->password);
            $core->password = Hash::make($request->password);
        }

        $hod->save();
        $core->save();

        return redirect()->route('hod.profile.show')->with('success', 'Profile updated successfully.');
    }

    // 🛡️ APPROVER 1
    public function showApprover1() {
        $user = Auth::user();
        $approver = Approver1::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('approver1.profile.show', compact('approver', 'core'));
    }
    public function editApprover1() {
        $user = Auth::user();
        $approver = Approver1::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('approver1.profile.edit', compact('approver', 'core'));
    }
    public function updateApprover1(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $approver = Approver1::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();

        $approver->name = $request->name;
        $approver->designation = $request->designation;
        $approver->description = $request->description;

        if ($request->filled('password')) {
            $approver->password = Hash::make($request->password);
            $core->password = Hash::make($request->password);
        }

        $approver->save();
        $core->save();

        return redirect()->route('approver1.profile.show')->with('success', 'Profile updated successfully.');
    }

    // 🛡️ APPROVER 2
    public function showApprover2() {
        $user = Auth::user();
        $approver2 = Approver2::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('approver2.profile.show', compact('approver2', 'core'));
    }
    public function editApprover2() {
        $user = Auth::user();
        $approver2 = Approver2::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();
        return view('approver2.profile.edit', compact('approver2', 'core'));
    }
    public function updateApprover2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $approver2 = Approver2::where('reg_no', $user->reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $user->reg_no)->first();

        $approver2->name = $request->name;
        $approver2->designation = $request->designation;
        $approver2->description = $request->description;

        if ($request->filled('password')) {
            $approver2->password = Hash::make($request->password);
            $core->password = Hash::make($request->password);
        }

        $approver2->save();
        $core->save();

        return redirect()->route('approver2.profile.show')->with('success', 'Profile updated successfully.');
    }
}
