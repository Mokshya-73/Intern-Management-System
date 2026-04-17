<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\InternProfile;
use App\Models\SupervisorProfile;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    // Display all complaints
    public function index()
    {
        // Fetch all complaints along with their related intern and supervisor profiles
        $complaints = Complaint::with('intern', 'supervisor')->get();
        return view('hod.complaints.index', compact('complaints'));
    }

    // Store a new complaint
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'intern_reg_no' => 'required|string',
            'complaint' => 'required|string',
            'supervisor_reg_no' => 'required|string',
        ]);

        // Create a new complaint
        Complaint::create([
            'intern_reg_no' => $request->intern_reg_no,
            'complaint' => $request->complaint,
            'supervisor_reg_no' => $request->supervisor_reg_no,
        ]);

        // Redirect back with success message
        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully!');
    }

    // Remove a complaint (mark as resolved)
    public function resolve($id, Request $request)
    {
        // Find the complaint by its ID
        $complaint = Complaint::findOrFail($id);

        // Check if the complaint is pending
        if ($complaint->status === 'pending') {
            // Mark the complaint as resolved
            $complaint->status = 'resolved';
            $complaint->reason_for_removal = $request->input('resolution'); // Save the resolution text
            $complaint->save();

            // Redirect to dashboard with success message
            return redirect()->route('hod.dashboard')->with('success', 'Complaint resolved successfully.');
        }

        // If the complaint is already resolved, return an error message
        return redirect()->route('hod.dashboard')->with('error', 'Complaint is already resolved.');
    }

    // Remove a complaint with a removal reason
    public function removeComplaint(Request $request, $id)
    {
        // Find the complaint by its ID
        $complaint = Complaint::findOrFail($id);

        // Validate the reason for removal
        $request->validate([
            'reason_for_removal' => 'required|string',
        ]);

        // Update the complaint to mark it as resolved with a removal reason
        $complaint->update([
            'status' => 'resolved',
            'reason_for_removal' => $request->reason_for_removal,
        ]);

        // Optionally notify the supervisor about the removal reason
        // Code to notify the supervisor goes here (e.g., via email or notification system)

        // Redirect back to the complaints list with a success message
        return redirect()->route('complaints.index')->with('success', 'Complaint resolved successfully!');
    }

    // Resolve a specific complaint with resolution text
    public function resolveComplaint($id, Request $request)
    {
        // Find the complaint by its ID
        $complaint = Complaint::findOrFail($id);

        // Check if the complaint is still pending
        if ($complaint->status === 'pending') {
            // Update the complaint's status to 'resolved'
            $complaint->status = 'resolved';

            // Save the resolution (reason for removal)
            $complaint->reason_for_removal = $request->input('resolution'); // Save the resolution text

            // Save the updated complaint
            $complaint->save();

            // Redirect back with success message
            return redirect()->route('hod.dashboard')->with('success', 'Complaint resolved successfully.');
        }

        // If the complaint is already resolved, return an error
        return redirect()->route('hod.dashboard')->with('error', 'Complaint is already resolved.');
    }

    public function internComplaints()
    {
        // Get the currently authenticated intern's profile
        $intern = auth()->user();

        // Retrieve the complaints related to this intern
        $complaints = Complaint::where('intern_reg_no', $intern->reg_no)
                                ->with('supervisor')  // You can load supervisor details if needed
                                ->orderByDesc('created_at')
                                ->paginate(10); // Paginate results for better UX

        return view('interns.complain.index', compact('complaints', 'intern'));
    }


}
