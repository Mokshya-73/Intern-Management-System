<?php

namespace App\Http\Controllers\Approver1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternSession;
use App\Models\ISession;
use App\Models\Intern;
use Illuminate\Support\Facades\Auth;

class Approver1Controller extends Controller
{
    public function dashboard()
    {
        // Fetch all intern sessions with related intern, session, and tasks
        $allInternSessions = \App\Models\InternSession::with(['intern', 'session', 'tasks'])
            ->get()
            ->groupBy('reg_no');

        // Filter only interns who have exactly 4 sessions AND all are approved by supervisor and HOD
        $qualifiedInterns = $allInternSessions->filter(function ($sessions) {
            return $sessions->count() === 4 &&
                $sessions->every(function ($session) {
                    return $session->is_approved && $session->hod_approved;
                });
        });

        $sessionList = \App\Models\ISession::all();

        return view('dashboards.approver1', [
            'internSessionsByRegNo' => $qualifiedInterns,
            'sessionList' => $sessionList,
        ]);
    }




    public function approve(Request $request, $sessionId)
{
    $session = InternSession::with('intern')->findOrFail($sessionId);
    $regNo = $session->reg_no;

    // Fetch all sessions for this intern
    $allSessions = InternSession::where('reg_no', $regNo)
        ->orderBy('session_id')
        ->get();

    // Validate session count
    if ($allSessions->count() < 4) {
        return $request->expectsJson()
            ? response()->json(['error' => 'All 4 sessions must exist before approval.'], 422)
            : back()->withErrors(['This intern must complete all 4 sessions before Approver 1 can approve.']);
    }

    // Ensure all sessions are approved by Supervisor and HOD
    $unapproved = $allSessions->filter(fn($s) => !$s->is_approved || !$s->hod_approved);

    if ($unapproved->isNotEmpty()) {
        return $request->expectsJson()
            ? response()->json(['error' => 'All 4 sessions must be approved by Supervisor and HOD.'], 422)
            : back()->withErrors(['All 4 sessions must be approved by both Supervisor and HOD before you can proceed.']);
    }

    // Approve the selected session
    $session->approver1_approved = true;
    $session->save();

    return $request->expectsJson()
        ? response()->json(['success' => true])
        : back()->with('success', 'Session approved successfully.');
}
public function approveAll($reg_no)
{
    $sessions = \App\Models\InternSession::where('reg_no', $reg_no)->get();

    foreach ($sessions as $session) {
        // Only approve if supervisor and hod already approved
        if ($session->is_approved && $session->hod_approved) {
            $session->approver1_approved = true;
            $session->save();
        }
    }

    return back()->with('success', 'All eligible sessions approved.');
}

public function unapproveAll($reg_no)
{
    \App\Models\InternSession::where('reg_no', $reg_no)
        ->update(['approver1_approved' => false]);

    return back()->with('success', 'All sessions unapproved.');
}



}
