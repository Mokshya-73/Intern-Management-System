<?php

namespace App\Http\Controllers\Approver2;

use Illuminate\Http\Request;
use App\Models\InternSession;
use App\Models\InternProfile;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Approver2Controller extends Controller
{
    public function index()
    {
        $interns = InternProfile::with(['internSessions' => function ($query) {
            $query->orderBy('session_id');
        }])->get()->filter(function ($intern) {
            return $intern->internSessions->count() === 4 &&
                $intern->internSessions->every(fn($s) => $s->approver1_approved);
        })->values(); // Reset collection keys

        if ($interns->isEmpty()) {
            $message = 'No interns found with all 4 sessions approved by Approver 1.';
            return view('dashboards.approver2', compact('interns', 'message'));
        }

        return view('dashboards.approver2', compact('interns'));
    }


    public function viewSessions($reg_no)
    {
        $sessions = InternSession::where('reg_no', $reg_no)
            ->with(['supervisor', 'tasks']) // add 'tasks' here
            ->get();

        return response()->json($sessions);
    }


    public function approveIntern(Request $request)
    {
        $reg_no = $request->reg_no;

        $intern = InternProfile::with('internSessions')->where('reg_no', $reg_no)->firstOrFail();
        $sessions = $intern->internSessions;

        // Check all sessions are approved by Approver 1
        $notApproved = $sessions->where('approver1_approved', false);
        if ($notApproved->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Not all sessions are approved by Approver 1']);
        }

        // Approve all sessions by Approver 2
        foreach ($sessions as $session) {
            $session->approver2_approved = true;
            $session->approver2_approved_at = now();
            $session->save();
        }

        // If certificate not generated, generate it
        if (!$intern->certificate_generated_at) {
            $pdf = Pdf::loadView('certificates.template', ['intern' => $intern]);

            $folder = storage_path("app/certificates/");
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $pdf->save("{$folder}{$reg_no}.pdf");

            $intern->certificate_generated_at = Carbon::now();
            $intern->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Approved the intern and generated certificate successfully.'
        ]);
    }
}
