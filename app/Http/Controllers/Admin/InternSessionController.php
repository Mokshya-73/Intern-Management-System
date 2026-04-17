<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternProfile;
use App\Models\ISession;
use App\Models\InternSession;
use App\Models\University;
use App\Models\Department;
use App\Models\Hod;
use App\Models\Supervisor;

class InternSessionController extends Controller
{
    // List all assigned intern sessions
    public function index()
    {
        $sessions = InternSession::with(['session', 'supervisor', 'universityLocation'])->orderBy('created_at', 'desc')->get();
        return view('admin.sessions.index', compact('sessions'));
    }


    public function create(Request $request)
    {
        $reg_no = $request->reg_no;
        $intern = null;

        if ($reg_no) {
            $intern = InternProfile::where('reg_no', $reg_no)
                ->orWhere('name', 'like', "%{$reg_no}%")
                ->first();

            if (!$intern) {
                return back()->withErrors(['reg_no' => 'Intern not found.']);
            }

            $assignedSessions = InternSession::where('reg_no', $intern->reg_no)->pluck('session_id')->toArray();
            $allSessions = ISession::orderBy('id')->get();

            $nextSession = null;
            foreach ($allSessions as $session) {
                if (!in_array($session->id, $assignedSessions)) {
                    $nextSession = $session;
                    break;
                }
            }

            if (!$nextSession) {
                return back()->withErrors(['reg_no' => 'All sessions are already assigned to this intern.']);
            }

            // Check for unapproved previous sessions
            $previousSessionIds = $allSessions->where('id', '<', $nextSession->id)->pluck('id');

            $unapprovedSession = InternSession::where('reg_no', $intern->reg_no)
                ->whereIn('session_id', $previousSessionIds)
                ->where(function ($query) {
                    $query->where('is_approved', 0)
                        ->orWhere('hod_approved', 0);
                })
                ->first();

            if ($unapprovedSession) {
                $sessionName = optional($unapprovedSession->session)->session_name ?? 'Previous session';
                $supervisorStatus = $unapprovedSession->is_approved ? '✅ Approved' : '❌ Not approved';
                $hodStatus = $unapprovedSession->hod_approved ? '✅ Approved' : '❌ Not approved';

                $message = "The session \"{$sessionName}\" is not fully approved. 
                    Supervisor Approval: {$supervisorStatus}, 
                    HOD Approval: {$hodStatus}. 
                    You cannot assign the next session.";

                return back()->withErrors(['reg_no' => $message]);
            }

            return view('admin.sessions.assign', [
                'intern' => $intern,
                'sessions' => collect([$nextSession]),
                'universities' => University::all(),
                'departments' => Department::all(),
                'supervisors' => Supervisor::all(),
            ]);
        }

        return view('admin.sessions.assign', [
            'intern' => null,
            'sessions' => [],
            'universities' => University::all(),
            'departments' => Department::all(),
            'supervisors' => Supervisor::all(),
        ]);
    }



    // Store a new session assignment
    public function store(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|exists:intern_profile,reg_no',
            'session_id' => 'required|exists:i_sessions,id',
            'sup_id' => 'required|exists:supervisor,id',
            'uni_id' => 'required|exists:universities,id',
            'department_id' => 'required|exists:departments,id',
            'location' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_path' => 'nullable|file|mimes:pdf,zip,docx|max:5120',
        ]);

        // Block session assignment if last one not approved
        $latest = InternSession::where('reg_no', $request->reg_no)->latest()->first();
        if ($latest && !$latest->is_approved) {
            return back()->withErrors(['The previous session has not been approved.']);
        }

        // Handle file upload
        $projectPath = null;
        if ($request->hasFile('project_path')) {
            $projectPath = $request->file('project_path')->store('intern_projects', 'public');
        }

        // Store the new intern session
        InternSession::create([
            'reg_no' => $request->reg_no,
            'session_id' => $request->session_id,
            'sup_id' => $request->sup_id,
            'uni_id' => $request->uni_id,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'project_name' => $request->project_name,
            'project_path' => $projectPath,
            'is_approved' => false,
        ]);

        return redirect()->route('admin.sessions.index')->with('success', 'Intern session assigned successfully.');
    }
    public function edit($id)
    {
        $session = InternSession::findOrFail($id);
        $intern = InternProfile::where('reg_no', $session->reg_no)
            ->with(['university', 'department.location'])
            ->first();

        $universities = University::all();
        $departments = Department::all();
        $supervisors = Supervisor::all();
        $sessions = ISession::all();

        return view('admin.sessions.edit', compact(
            'session', 'intern', 'universities', 'departments', 'supervisors', 'sessions'
        ));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'session_id' => 'required|exists:i_sessions,id',
            'sup_id' => 'required|exists:supervisor,id',
            'uni_id' => 'required|exists:universities,id',
            'department_id' => 'required|exists:departments,id',
            'location' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_path' => 'nullable|file|mimes:pdf,zip,docx|max:5120',
        ]);

        $session = InternSession::findOrFail($id);

        // If new file uploaded
        if ($request->hasFile('project_path')) {
            $projectPath = $request->file('project_path')->store('intern_projects', 'public');
            $session->project_path = $projectPath;
        }

        $session->update([
            'session_id' => $request->session_id,
            'sup_id' => $request->sup_id,
            'uni_id' => $request->uni_id,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'project_name' => $request->project_name,
        ]);

        return redirect()->route('admin.sessions.index')->with('success', 'Intern session updated successfully.');
    }
    public function destroy($id)
    {
        $session = InternSession::findOrFail($id);
        $session->delete();

        return redirect()->route('admin.sessions.index')->with('success', 'Intern session deleted successfully.');
    }
    public function approveByHOD($id)
    {
        $session = \App\Models\InternSession::findOrFail($id);

        // Get the actual HOD model based on reg_no
        $hod = Hod::where('reg_no', auth()->user()->reg_no)->first();

        if (!$hod) {
            abort(403, 'HOD not found.');
        }

        $session->update([
            'hod_approved' => 1,
            'hod_id' => $hod->id,
        ]);

        return back()->with('success', 'Session approved by HOD.');
    }



}
