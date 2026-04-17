<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ISession;
use App\Models\InternProfile;
use App\Models\InternSession;
use App\Models\InternComplaint;

class InternComplaintController extends Controller
{
    public function index()
    {
        // Get the logged-in intern's profile based on their reg_no
        $intern = InternProfile::where('reg_no', auth()->user()->reg_no)->first();

        // Get all sessions (if needed for dropdown/filter)
        $iSessions = ISession::all();

        // Fetch complaints made by the current intern
        $complaints = Complaint::where('intern_reg_no', auth()->user()->reg_no)
                        ->latest()
                        ->paginate(10);

        // Get intern session details
        $internSessions = InternSession::with(['supervisor', 'session', 'department'])
            ->where('reg_no', auth()->user()->reg_no) // Use reg_no to filter intern sessions
            ->get();

        // Get the first session details, or a default message
        $firstSessionId = $iSessions->first()->id ?? null;
        $firstSessionName = $firstSessionId ? $iSessions->firstWhere('id', $firstSessionId)->session_name . ' (' . $iSessions->firstWhere('id', $firstSessionId)->session_time_period . ')' : 'Select Session';

        // Return the view with all required variables
        return view('interns.complain.index', [
            'complaints' => $complaints,
            'iSessions' => $iSessions,
            'intern' => $intern,
            'internSessions' => $internSessions,
            'firstSessionName' => $firstSessionName,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'intern_session_id' => 'required|exists:intern_sessions,id',
            'message' => 'required|string|max:1000',
        ]);

        InternComplaint::create([
            'intern_reg_no' => auth()->user()->reg_no,
            'intern_session_id' => $request->intern_session_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Complaint submitted to HOD.');
    }
    public function history()
    {
        $regNo = auth()->user()->reg_no;

        $complaints = \App\Models\InternComplaint::with('internSession.session')
            ->where('intern_reg_no', $regNo)
            ->latest()
            ->paginate(10);

        return view('interns.complain.history', compact('complaints'));
    }

}
