<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\InternComplaint;

class InternComplaintController extends Controller
{
    public function index()
    {
        $hodId = Auth::id();

        $complaints = InternComplaint::with(['intern', 'internSession.session'])
            ->whereHas('internSession.department.departmentHod', function ($q) use ($hodId) {
                $q->where('hod_id', $hodId)->where('is_active', 1);
            })
            ->latest()
            ->paginate(10);

        return view('hod.complaints.index', compact('complaints'));
    }

    public function resolve($id)
    {
        $complaint = InternComplaint::findOrFail($id);
        $complaint->update(['status' => 'resolved']);

        return back()->with('success', 'Complaint marked as resolved.');
    }
}
