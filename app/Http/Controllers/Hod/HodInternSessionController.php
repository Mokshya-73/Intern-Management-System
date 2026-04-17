<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternSession;

class HodInternSessionController extends Controller
{
    public function approve($id)
    {
        $session = \App\Models\InternSession::findOrFail($id);

        // Ensure logged in
        if (!auth()->check()) {
            abort(403); // or redirect to login will log user out
        }

        $session->hod_approved = true;
        $session->save();

        return back()->with('success', 'Intern session approved successfully.');
    }

}
