<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Supervisor;
use App\Models\InternProfile;
use Illuminate\Support\Facades\Auth;
use App\Models\InternSession;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        return view('dashboards.supervisor');
    }

    // Store a complaint
    public function storeComplaint(Request $request)
    {
        $request->validate([
            'intern_reg_no' => 'required|exists:intern_profile,reg_no',
            'complaint' => 'required|string|max:1000',
        ]);

        $supervisorRegNo = auth()->user()->reg_no;

        Complaint::create([
            'intern_reg_no' => $request->intern_reg_no,
            'supervisor_reg_no' => $supervisorRegNo,
            'complaint' => $request->complaint,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Complaint submitted successfully.');
    }

    public function showComplaints()
    {
        $supervisor = \App\Models\Supervisor::where('reg_no', auth()->user()->reg_no)->first();

        // Fetch the complaints where the supervisor is involved (either as the reporting supervisor or related to the intern)
        $complaints = Complaint::where('supervisor_reg_no', $supervisor->reg_no)
                                ->with('intern', 'supervisor')
                                ->get();

        return view('supervisor.complaints.history', compact('complaints'));
    }
    public function history()
    {
        $supervisorRegNo = auth()->user()->reg_no;

        $complaints = Complaint::where('supervisor_reg_no', $supervisorRegNo)
            ->with('intern')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor.complaints.history', compact('complaints'));
    }
    public function myInternship()
    {
        $reg_no = auth()->user()->reg_no;

        $intern = InternProfile::where('reg_no', $reg_no)->first();

        if (!$intern) {
            return view('supervisor.my_internship', ['intern' => null, 'internSessions' => []]);
        }

        $internSessions = InternSession::with(['session', 'tasks', 'supervisor'])
            ->where('reg_no', $reg_no)
            ->get();

        return view('supervisor.my_internship', compact('intern', 'internSessions'));
    }

}
