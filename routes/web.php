<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InternSessionController;
use App\Http\Controllers\Supervisor\TaskController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\InternProfile;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Hod\HodDashboardController;
use App\Http\Controllers\Supervisor\SupervisorController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UniversityStructureController;
use App\Http\Controllers\Approver1\Approver1Controller;
use App\Http\Controllers\Approver2\Approver2Controller;
use App\Http\Controllers\Certificate\CertificateControlle;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Intern\InternComplaintController;
use App\Http\Controllers\Hod\InternComplaintController as HodComplaintController;
use App\Http\Controllers\Hod\HodInternSessionController;



// Homepage
// Route::get('/', fn () => view('welcome'));
Route::get('/', function () {
    return redirect('/login');
});

// Debug route to check logged-in user
Route::get('/check-auth', fn () => auth()->user());

// -----------------------------
// Google OAuth (Login + Connect)
// -----------------------------

// 🔐 Redirect to Google (mode = login | connect)
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');

// ✅ Callback from Google (handles both login & connect)
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// 🔓 Disconnect Google account (must be logged in)
Route::post('/auth/google/disconnect', [GoogleAuthController::class, 'disconnectGoogleAccount'])->middleware('auth')->name('google.disconnect');

// --------------------
// Login + Logout Routes
// --------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------
// Intern Dashboard (Role 1)
// -------------------------
Route::middleware(['auth', RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/intern/dashboard', function () {
        $user = Auth::user();

        if (!$user || !$user->reg_no) {
            abort(403, 'Unauthorized');
        }

        $intern = \App\Models\InternProfile::where('reg_no', $user->reg_no)->first();

        if (!$intern) {
            return redirect()->back()->with('error', 'Intern profile not found.');
        }

        // Retrieve sessions for the intern
        $iSessions = \App\Models\InternSession::where('reg_no', $intern->id)->get();

        // Pass $intern and $iSessions to the view
        return view('dashboards.intern', compact('intern', 'iSessions'));
    })->name('intern.dashboard');

    // Intern Profile Routes
    Route::prefix('intern')->group(function () {
        Route::get('/profile', [ProfileController::class, 'showIntern'])->name('intern.profile.show');
        Route::post('/profile/update', [ProfileController::class, 'updateIntern'])->name('intern.profile.update');
        Route::get('/profile/edit', [ProfileController::class, 'editIntern'])->name('intern.profile.edit');
        Route::post('/intern-complaints', [InternComplaintController::class, 'store'])->name('intern_complaints.store');
        Route::get('/complaints/history', [InternComplaintController::class, 'history'])->name('intern_complaints.history');

    });
});


// -----------------------------
// Other Dashboards by Role ID
// -----------------------------
// Route::middleware([RoleMiddleware::class . ':2'])->get('/supervisor/dashboard', fn () => view('dashboards.supervisor'))->name('supervisor.dashboard');
Route::middleware([RoleMiddleware::class . ':2'])->prefix('supervisor')->name('supervisor.')->group(function () {

    // Supervisor Dashboard (already defined)
    Route::get('/dashboard', fn () => view('dashboards.supervisor'))->name('dashboard');

    // View & Review assigned intern session
    Route::get('/review/{session}', [TaskController::class, 'editTasks'])->name('review');

    // Submit task ratings + feedback + approve session
    Route::post('/review/{session}', [TaskController::class, 'updateTasks'])->name('review.update');
    Route::get('/my-internship', [SupervisorController::class, 'myInternship'])->name('myInternship');

    // Supervisor Profile
    Route::get('/profile', [ProfileController::class, 'showSupervisor'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'editSupervisor'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateSupervisor'])->name('profile.update');

});

Route::middleware([RoleMiddleware::class . ':3'])->get('/hod/dashboard', [HodDashboardController::class, 'index'])->name('hod.dashboard');

Route::middleware([RoleMiddleware::class . ':3'])->prefix('hod')->name('hod.')->group(function () {

    // HOD Profile
    Route::get('/profile', [ProfileController::class, 'showHOD'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'editHOD'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateHOD'])->name('profile.update');
    Route::get('/intern-complaints', [HodComplaintController::class, 'index'])->name('intern_complaints.index');
    Route::post('/intern-complaints/{id}/resolve', [HodComplaintController::class, 'resolve'])->name('intern_complaints.resolve');
    // Route::post('/intern-sessions/{id}/approve', [HodInternSessionController::class, 'approve'])->name('intern-sessions.approve');


});

// ------------------------
// Approver 1
// -----------------------
Route::middleware([RoleMiddleware::class . ':4'])->group(function () {
    Route::get('/approver1/dashboard', [Approver1Controller::class, 'dashboard'])->name('approver1.dashboard');
    Route::post('/approver1/approve/{sessionId}', [Approver1Controller::class, 'approve'])->name('approver1.approve');
    Route::post('/approver1/approve/{id}', [Approver1Controller::class, 'approve'])->name('approver1.approve');


    Route::get('/approver1/profile', [ProfileController::class, 'showApprover1'])->name('approver1.profile.show');
    Route::get('/approver1/profile/edit', [ProfileController::class, 'editApprover1'])->name('approver1.profile.edit');
    Route::post('/approver1/profile/update', [ProfileController::class, 'updateApprover1'])->name('approver1.profile.update');
});

// ------------------------
// Approver 2
// -----------------------
Route::middleware([RoleMiddleware::class . ':5'])->group(function () {
    Route::get('/approver2/dashboard', [Approver2Controller::class, 'index'])->name('approver2.dashboard');
    Route::get('/approver2/interns', [Approver2Controller::class, 'index']);
    Route::get('/approver2/intern/{reg_no}/sessions', [Approver2Controller::class, 'viewSessions']);
    Route::post('/approver2/approve', [Approver2Controller::class, 'approveIntern']);

    // Approver 2 Profile
    Route::get('/approver2/profile', [ProfileController::class, 'showApprover2'])->name('approver2.profile.show');
    Route::get('/approver2/profile/edit', [ProfileController::class, 'editApprover2'])->name('approver2.profile.edit');
    Route::post('/approver2/profile/update', [ProfileController::class, 'updateApprover2'])->name('approver2.profile.update');


    Route::post('/approver1/approve-all/{reg_no}', [Approver1Controller::class, 'approveAll'])->name('approver1.approveAll');
    Route::post('/approver1/unapprove-all/{reg_no}', [Approver1Controller::class, 'unapproveAll'])->name('approver1.unapproveAll');

});


Route::middleware([RoleMiddleware::class . ':6'])->get('/admin/dashboard', fn () => view('dashboards.admin'))->name('admin.dashboard');

// ------------------------
// Admin - User Management
// ------------------------
Route::prefix('admin')->middleware([RoleMiddleware::class . ':6'])->group(function () {
    // Interns
    Route::get('/interns', [UserManagementController::class, 'indexInterns'])->name('admin.interns.index');
    Route::get('/interns/create', [UserManagementController::class, 'createIntern'])->name('admin.interns.create');
    Route::post('/interns', [UserManagementController::class, 'storeIntern'])->name('admin.interns.store');
    Route::get('/interns/{reg_no}/edit', [UserManagementController::class, 'editIntern'])->name('admin.interns.edit');
    Route::post('/interns/{reg_no}/update', [UserManagementController::class, 'updateIntern'])->name('admin.interns.update');
    Route::delete('/interns/{reg_no}/delete', [UserManagementController::class, 'deleteIntern'])->name('admin.interns.delete');

    // Supervisors
    Route::get('/supervisors', [UserManagementController::class, 'indexSupervisors'])->name('admin.supervisors.index');
    Route::get('/supervisors/create', [UserManagementController::class, 'createSupervisor'])->name('admin.supervisors.create');
    Route::post('/supervisors', [UserManagementController::class, 'storeSupervisor'])->name('admin.supervisors.store');
    Route::get('/supervisors/{reg_no}/edit', [UserManagementController::class, 'editSupervisor'])->name('admin.supervisors.edit');
    Route::post('/supervisors/{reg_no}/update', [UserManagementController::class, 'updateSupervisor'])->name('admin.supervisors.update');
    Route::delete('/supervisors/{reg_no}/delete', [UserManagementController::class, 'deleteSupervisor'])->name('admin.supervisors.delete');

    // HODs (Heads of Department)
    Route::get('/hods', [UserManagementController::class, 'indexHods'])->name('admin.hods.index');
    Route::get('/hods/create', [UserManagementController::class, 'createHod'])->name('admin.hods.create');
    Route::post('/hods', [UserManagementController::class, 'storeHod'])->name('admin.hods.store');
    Route::get('/hods/{id}/edit', [UserManagementController::class, 'editHod'])->name('admin.hods.edit');
    Route::post('/hods/{id}/update', [UserManagementController::class, 'updateHod'])->name('admin.hods.update');
    Route::delete('/hods/{id}/delete', [UserManagementController::class, 'deleteHod'])->name('admin.hods.delete');

    // University Management
    Route::get('/universities', [UniversityStructureController::class, 'index'])->name('admin.universities.index');
    Route::get('/universities/create', [UniversityStructureController::class, 'createUniversity'])->name('admin.universities.create');
    Route::post('/universities/store', [UniversityStructureController::class, 'storeUniversity'])->name('admin.universities.store');

    // Locations
    Route::get('/universities/{university_id}/locations/create', [UniversityStructureController::class, 'createLocation'])->name('admin.locations.create');
    Route::post('/universities/{university_id}/locations/store', [UniversityStructureController::class, 'storeLocation'])->name('admin.locations.store');

    // Departments
    Route::get('/admin/universities/{university}/locations', function ($universityId) {
        return \App\Models\UniversityLocation::where('university_id', $universityId)->get();
    });
    Route::get('/structure/departments/create', [UniversityStructureController::class, 'createDepartment'])->name('admin.structure.departments.create');
    Route::post('/structure/departments/store', [UniversityStructureController::class, 'storeDepartment'])->name('admin.structure.departments.store');

    // Assign HOD & Supervisors
    Route::match(['get', 'post'], '/structure/assign', [UniversityStructureController::class, 'assignStructure'])->name('admin.structure.assign');
    Route::post('/structure/store', [UniversityStructureController::class, 'storeStructure'])->name('admin.structure.store');

    Route::get('/structure/remove/{type}/{id}', [UniversityStructureController::class, 'showRemoveStructure'])->name('admin.structure.remove');
    Route::post('/structure/remove', [UniversityStructureController::class, 'removeStructure'])->name('admin.structure.remove.store');

     // Assign HOD
    Route::get('/assign-hod', [UniversityStructureController::class, 'showAssignHODForm'])->name('admin.structure.assign.hod');
    Route::post('/assign-hod', [UniversityStructureController::class, 'storeAssignHOD'])->name('admin.structure.assign.hod.store');

    // Assign Supervisor
    Route::get('/admin/structure/assign-supervisor', [UniversityStructureController::class, 'showAssignSupervisorForm'])->name('admin.structure.assign_supervisor');
    Route::post('/admin/structure/assign-supervisor', [UniversityStructureController::class, 'storeAssignSupervisor'])->name('admin.structure.assign_supervisor.store');

    // View Structure
    Route::get('/view', [UniversityStructureController::class, 'viewStructure'])->name('admin.structure.view');

    // Remove HOD (soft delete)
    Route::post('/remove-hod/{department_id}', [UniversityStructureController::class, 'removeHOD'])->name('admin.structure.remove.hod');

    // Remove Supervisor (soft delete)
    Route::post('/remove-supervisor/{supervisor_id}', [UniversityStructureController::class, 'removeSupervisor'])->name('admin.structure.remove.supervisor');

    Route::get('/structure/assign', [UniversityStructureController::class, 'assign'])->name('admin.structure.assign');
    // Route::get('/structure/assign-hod', [UniversityStructureController::class, 'assignHOD'])->name('admin.structure.assignHOD');
    Route::get('/structure/assign-hod', [UniversityStructureController::class, 'showAssignHODForm'])->name('admin.structure.assignHOD');
    Route::post('/structure/assign-hod', [UniversityStructureController::class, 'storeAssignHOD'])->name('admin.structure.assignHOD.store');
    Route::post('/structure/change-hod', [UniversityStructureController::class, 'changeHOD'])->name('admin.structure.change.hod');


    Route::get('/structure/assign-supervisor', [UniversityStructureController::class, 'showAssignSupervisorForm'])->name('admin.structure.assignSupervisor');

    Route::get('/structure/view', [UniversityStructureController::class, 'viewStructure'])->name('admin.structure.view');

    Route::post('/promote-intern/{reg_no}', [UserManagementController::class, 'promoteToSupervisor'])->name('admin.promote.intern');

    Route::get('/dashboard/promote-intern', [UserManagementController::class, 'dashboardInternSearch'])->name('admin.dashboard.promote');
    Route::post('/dashboard/promote-intern/{reg_no}', [UserManagementController::class, 'promoteToSupervisor'])->name('admin.dashboard.promote.action');


    // Universities
    Route::get('/unis/create', [UniversityController::class, 'create'])->name('admin.unis.create');
    Route::post('/unis/store', [UniversityController::class, 'store'])->name('admin.unis.store');
    Route::get('/unis/{id}/edit', [UniversityController::class, 'edit'])->name('admin.unis.edit');
    Route::put('/unis/{id}', [UniversityController::class, 'update'])->name('admin.unis.update');
    Route::delete('/unis/{id}', [UniversityController::class, 'destroy'])->name('admin.unis.destroy');

    // Locations
    Route::post('/locs/store', [UniversityController::class, 'storeLocation'])->name('admin.locs.store');
    Route::get('/locs/{id}/edit', [UniversityController::class, 'editLocation'])->name('admin.locs.edit');
    Route::put('/locs/{id}', [UniversityController::class, 'updateLocation'])->name('admin.locs.update');
    Route::delete('/locs/{id}', [UniversityController::class, 'destroyLocation'])->name('admin.locs.destroy');


    // User Email & Password Credential Update
    Route::get('/users/{reg_no}/edit-credentials', [UserManagementController::class, 'editCredentials'])->name('admin.users.edit.credentials');
    Route::put('/users/{reg_no}/update-email', [UserManagementController::class, 'updateEmail'])->name('admin.users.update.email');
    Route::put('/users/{reg_no}/update-password', [UserManagementController::class, 'updatePassword'])->name('admin.users.update.password');

    Route::get('/get-locations/{university_id}', [UniversityStructureController::class, 'getLocations']);
    Route::get('/get-departments/{university_id}/{location_id}', [UniversityStructureController::class, 'getDepartments']);
    Route::get('/get-supervisors/{department_id}', [UniversityStructureController::class, 'getSupervisorsByDepartment']);

});


    // Show reset password form (GET /password/reset/{token}?email=...)
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    // Handle password reset form submission (POST)
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // (Optional) Show form to request reset link manually
    Route::get('/password/reset', function () {
            return view('auth.passwords.email');
        })->name('password.request');
        Route::post('/password/reveal', function (Request $request) {
        $request->validate([
            'nic' => 'required|string',
            'actual_password' => 'required|string',
        ]);

        $email = session('email');
        $intern = InternProfile::where('email', $email)->first();

        if ($intern && $request->nic === $intern->nic) {
            session()->flash('revealed_password', $request->actual_password);
        } else {
            session()->flash('revealed_password', 'NIC mismatch or not found.');
        }

        return back();
    })->name('password.reveal');

    // (Optional) Send reset link
    Route::post('/password/email', function (Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');




// Route::middleware(['auth', 'role:1'])->prefix('intern')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'showIntern'])->name('intern.profile.show');
//     Route::post('/profile/update', [ProfileController::class, 'updateIntern'])->name('intern.profile.update');
// });




// Grouped under /admin/intern-sessions/
Route::prefix('admin/intern-sessions')->name('admin.sessions.')->group(function () {
 // 1. Show assign form for selected intern (search happens inside create method)
 Route::get('/create', [InternSessionController::class,
'create'])->name('create');
 // 2. Submit assignment form (with optional project upload)
 Route::post('/assign', [InternSessionController::class,
'store'])->name('store');
 // 3. View all assigned sessions
 Route::get('/', [InternSessionController::class, 'index'])->name('index');
 // 4. Edit a specific assigned session
 Route::get('/{id}/edit', [InternSessionController::class,
'edit'])->name('edit');
 // 5. Update session data
 Route::put('/{id}', [InternSessionController::class, 'update'])->name('update');
 // 6. Delete session
 Route::delete('/{id}', [InternSessionController::class,
'destroy'])->name('destroy');
});


Route::prefix('supervisor/tasks')->name('supervisor.tasks.')->group(function () {
    Route::get('/edit/{sessionId}', [TaskController::class, 'editTasks'])->name('edit');
    Route::post('/update/{sessionId}', [TaskController::class, 'updateTasks'])->name('update');
});
Route::post('/supervisor/approve/{session}', [TaskController::class, 'approve'])->name('supervisor.approve');

// -------------------------
// HOD Routes (Role 3)
// -------------------------
Route::middleware([RoleMiddleware::class . ':3'])->group(function () {
    // Complaint Routes for HOD
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints/{id}/remove', [ComplaintController::class, 'removeComplaint'])->name('complaints.remove');
    Route::post('/hod/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
    Route::post('/hod/intern-sessions/{id}/approve', [InternSessionController::class, 'approveByHOD'])->name('hod.intern-sessions.approve');



    // HOD Dashboard Route
    Route::get('/dashboard/hod', [HodDashboardController::class, 'index'])->name('hod.dashboard');
});

// -------------------------
// Supervisor Routes
// -------------------------
Route::middleware([RoleMiddleware::class . ':2'])->group(function () {
    // Supervisor Complaints Routes
    Route::post('/supervisor/complaints/store', [SupervisorController::class, 'storeComplaint'])->name('supervisor.complaints.store');
    Route::get('/supervisor/complaints/history', [SupervisorController::class, 'history'])->name('supervisor.complaints.history');
});


// Approver 1
Route::get('/admin/approver1', [UserManagementController::class, 'indexApprover1'])->name('admin.approver1.index');
Route::get('/admin/approver1/create', [UserManagementController::class, 'createApprover1'])->name('admin.approver1.create');
Route::post('/admin/approver1/store', [UserManagementController::class, 'storeApprover1'])->name('admin.approver1.store');
Route::get('/admin/approver1/{reg_no}/edit', [UserManagementController::class, 'editApprover1'])->name('admin.approver1.edit');
Route::put('/admin/approver1/{reg_no}/update', [UserManagementController::class, 'updateApprover1'])->name('admin.approver1.update');
Route::delete('/admin/approver1/{reg_no}/delete', [UserManagementController::class, 'deleteApprover1'])->name('admin.approver1.delete');

// Approver 2
Route::get('/admin/approver2', [UserManagementController::class, 'indexApprover2'])->name('admin.approver2.index');
Route::get('/admin/approver2/create', [UserManagementController::class, 'createApprover2'])->name('admin.approver2.create');
Route::post('/admin/approver2/store', [UserManagementController::class, 'storeApprover2'])->name('admin.approver2.store');
Route::get('/admin/approver2/{reg_no}/edit', [UserManagementController::class, 'editApprover2'])->name('admin.approver2.edit');
Route::put('/admin/approver2/{reg_no}/update', [UserManagementController::class, 'updateApprover2'])->name('admin.approver2.update');
Route::delete('/admin/approver2/{reg_no}/delete', [UserManagementController::class, 'deleteApprover2'])->name('admin.approver2.delete');


Route::middleware('auth')->group(function() {
    // Intern's complaints page
    Route::get('intern/complaints', [ComplaintController::class, 'internComplaints'])->name('intern.complaints.index');
});

// Downoad Cerificate
Route::get('/certificate/download/{reg_no}', [CertificateControlle::class, 'download'])->name('certificate.download');
