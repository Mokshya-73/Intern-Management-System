<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Hod;
use App\Models\Supervisor;
use App\Models\Complaint;
use App\Models\InternComplaint;
use App\Models\InternSession;

class HodDashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $hod = Hod::where('reg_no', Auth::user()->reg_no)->first();

        if (!$hod) {
            return redirect()->route('home')->with('error', 'HOD not found.');
        }

        // Get supervisors assigned to this HOD
        $supervisors = $hod->supervisors()->with(['interns' => function ($query) use ($search) {
            $query->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('intern_profile.reg_no', 'like', "%{$search}%");
            })->with([
                'complaints' => fn($q) => $q->where('status', 'pending'),
                'core',
                'internSessions' => fn($q) => $q->with(['session', 'tasks'])
            ]);
        }])->get();

        if ($supervisors->isEmpty()) {
            $supervisors = collect();
        }

        // Supervisor to HOD complaints
        $complaints = Complaint::with(['intern', 'supervisor'])
            ->where('status', 'pending')
            ->whereHas('intern', function ($query) use ($supervisors) {
                $query->whereHas('internSessions', function ($q) use ($supervisors) {
                    $q->whereIn('sup_id', $supervisors->pluck('id'));
                });
            })
            ->get();

        // Intern to HOD complaints
        $internComplaints = InternComplaint::with(['intern', 'internSession.session'])
            ->whereHas('internSession.department.departmentHod', function ($q) use ($hod) {
                $q->where('hod_id', $hod->id)->where('is_active', 1);
            })
            ->where('status', 'pending')
            ->get();

        // ✅ Approved Intern Sessions needing HOD review
        $approvedSessions = InternSession::with(['intern', 'supervisor', 'session'])
            ->where('is_approved', 1)
            ->whereHas('department.departmentHod', function ($q) use ($hod) {
                $q->where('hod_id', $hod->id)->where('is_active', 1);
            })
            ->get();

        return view('dashboards.hod', compact(
            'supervisors',
            'complaints',
            'internComplaints',
            'approvedSessions',
            'search'
        ));
    }
}
