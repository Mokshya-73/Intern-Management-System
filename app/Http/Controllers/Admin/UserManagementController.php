<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserCoreData;
use App\Models\InternProfile;
use App\Models\Supervisor;
use App\Models\University;
use App\Models\UniversityLocation;
use App\Models\Department;
use App\Models\Specialization;
use App\Models\Approver1;
use App\Models\Approver2;
use App\Models\DepartmentHod;
use Illuminate\Support\Facades\Hash;
use App\Models\Hod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Complaint;
use App\Models\Unis;
use App\Models\UniLoc;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\InternAccountCreated;
use App\Mail\SupervisorAccountCreated;
use App\Mail\HodAccountCreated;
use App\Mail\Approver1AccountCreated;
use App\Mail\Approver2AccountCreated;

class UserManagementController extends Controller
{
    // ---------------------------
    // INTERN FUNCTIONS
    // ---------------------------

    public function indexInterns()
    {
        $ongoingInterns = InternProfile::where('is_active', 1)->get();   // Currently active interns
        $previousInterns = InternProfile::where('is_active', 0)->get();  // Promoted or inactive interns
        $interns = InternProfile::all();

        return view('admin.interns.index', compact('ongoingInterns', 'previousInterns'));
    }


    public function createIntern(Request $request)
    {
        $locations = UniLoc::with('unis')->get(); // use 'unis' not 'university'

        $supervisors = Supervisor::all();
        $startDate = $request->input('training_start_date');
        $endDateOptions = [];

        if ($startDate) {
            $start = Carbon::parse($startDate);
            $endDateOptions = [
                $start->copy()->addMonths(3)->format('Y-m-d') => 'After 3 Months',
                $start->copy()->addMonths(6)->format('Y-m-d') => 'After 6 Months',
                $start->copy()->addMonths(9)->format('Y-m-d') => 'After 9 Months',
                $start->copy()->addYear()->format('Y-m-d') => 'After 1 Year',
            ];
        }

        return view('admin.interns.create', compact(
            'locations', 'supervisors', 'startDate', 'endDateOptions'
        ));
    }


    public function storeIntern(Request $request)
{
    $request->validate([
        'reg_no' => 'required|string|unique:user_core_data,reg_no|unique:intern_profile,reg_no',
        'email' => 'required|email|unique:user_core_data,email',
        'name' => 'required|string|max:255',
        'certificate_name' => 'required|string|max:255',
        'mobile' => 'required|string|max:20',
        'nic' => 'required|string|max:20',
        'city' => 'required|string|max:100',
        'training_start_date' => 'required|date',
        'duration' => 'required|in:3_months,6_months,9_months,1_year',
        'description' => 'nullable|string',
        'uni_loc_id' => 'required|exists:uni_locs,id',
    ]);

    // 🔁 Get university from uni_loc_id
    $uniLoc = UniLoc::findOrFail($request->uni_loc_id);
    $uni_id = $uniLoc->uni_id;

    // 🔐 Generate password and hash
    $initialPassword = Str::random(10);
    $hashedPassword = Hash::make($initialPassword);

    // 🆔 Create in user_core_data
    UserCoreData::create([
        'reg_no' => $request->reg_no,
        'email' => $request->email,
        'role_id' => 1, // intern
        'password' => $hashedPassword,
    ]);

    // 📄 Calculate end date
    $startDate = \Carbon\Carbon::parse($request->training_start_date);
    $endDate = match ($request->duration) {
        '3_months' => $startDate->copy()->addMonths(3),
        '6_months' => $startDate->copy()->addMonths(6),
        '9_months' => $startDate->copy()->addMonths(9),
        '1_year'   => $startDate->copy()->addYear(),
    };

    // 👤 Create intern profile
    InternProfile::create([
        'reg_no' => $request->reg_no,
        'email' => $request->email,
        'name' => $request->name,
        'certificate_name' => $request->certificate_name,
        'mobile' => $request->mobile,
        'nic' => $request->nic,
        'city' => $request->city,
        'training_start_date' => $startDate->format('Y-m-d'),
        'training_end_date' => $endDate->format('Y-m-d'),
        'description' => $request->description,
        'role_id' => 1,
        'is_active' => 1,
        'status' => 'Active',
        'password' => $hashedPassword,
        'previous_passwords' => json_encode([$hashedPassword]),
        'uni_id' => $uni_id,
        'uni_loc_id' => $request->uni_loc_id,
    ]);

    // 🔐 Optional email verification
    $token = Str::random(64);
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => Hash::make($token), 'created_at' => now()]
    );

    Mail::to($request->email)->send(new InternAccountCreated(
        $request->reg_no,
        $initialPassword,
        $token,
        $request->email
    ));

    return redirect()->route('admin.interns.index')->with('success', 'Intern added and credentials sent.');
}


    public function editIntern($reg_no)
    {
        $intern = InternProfile::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();
        return view('admin.interns.edit', compact('intern', 'core'));
    }


    public function updateIntern(Request $request, $reg_no)
    {
        $intern = InternProfile::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'email' => 'required|email|unique:user_core_data,email,' . $core->id,
            'name' => 'required',
            'certificate_name' => 'required|string|max:100',
            'mobile' => 'required',
            'nic' => 'required',
            'city' => 'required',
            'training_start_date' => 'required|date',
            'training_end_date' => 'required|date',
        ]);


        // Update core account
        $core->email = $request->email;
        if ($request->filled('password')) {
            $core->password = Hash::make($request->password);
        }
        $core->save();

        // Update intern profile
        $intern->update([
            'name' => $request->name,
            'certificate_name' => $request->certificate_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'nic' => $request->nic,
            'city' => $request->city,
            'training_start_date' => $request->training_start_date,
            'training_end_date' => $request->training_end_date,
            'description' => $request->description,
        ]);


        return redirect()->route('admin.interns.index')->with('success', 'Intern updated successfully.');
    }


    public function deleteIntern($reg_no)
    {
        // Delete complaints first
        Complaint::where('intern_reg_no', $reg_no)->delete();

    // Then delete intern data
    UserCoreData::where('reg_no', $reg_no)->delete();
    InternProfile::where('reg_no', $reg_no)->delete();

    return redirect()->route('admin.interns.index')->with('success', 'Intern deleted successfully.');
    }


    // ---------------------------
    // SUPERVISOR FUNCTIONS
    // ---------------------------

    public function indexSupervisors()
    {
        $supervisors = Supervisor::with('core')->get();
        return view('admin.supervisors.index', compact('supervisors'));
    }

    public function createSupervisor(Request $request)
    {
        $universities = University::all();
        $selectedUniversity = null;
        $locations = [];
        $generatedRegNo = $this->generateSupervisorRegNo();

        if ($request->has('university_id')) {
            $selectedUniversity = University::find($request->university_id);
            $locations = $selectedUniversity ? $selectedUniversity->locations : [];
        }

        return view('admin.supervisors.create', compact(
            'universities',
            'selectedUniversity',
            'locations',
            'generatedRegNo'
        ));
    }

    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|digits:5|unique:user_core_data,reg_no',
            'email' => 'required|email|unique:user_core_data,email',
            'name' => 'required|string|max:255',
            'university' => 'required|string',
            'location' => 'nullable|string',
            'designation' => 'required|string',
        ]);

        $password = Str::random(8); // Auto-generate secure password

        // Save in user_core_data
        UserCoreData::create([
            'reg_no' => $request->reg_no,
            'role_id' => 2, // Supervisor
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Save in supervisor profile table
        Supervisor::create([
            'reg_no' => $request->reg_no,
            'name' => $request->name,
            'university' => $request->university,
            'location' => $request->location,
            'designation' => $request->designation,
        ]);

        // Send welcome email with credentials
        Mail::to($request->email)->send(new SupervisorAccountCreated(
            $request->name,
            $request->reg_no,
            $request->email,
            $password
        ));

        // Show success with login credentials
        return redirect()->route('admin.supervisors.index')->with('success',
            "Supervisor added successfully.\n\nLogin Credentials:\nReg No: {$request->reg_no}\nEmail: {$request->email}\nPassword: {$password}"
        );
}

    public function editSupervisor($reg_no)
    {
        $supervisor = Supervisor::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();
        return view('admin.supervisors.edit', compact('supervisor', 'core'));
    }

    public function updateSupervisor(Request $request, $reg_no)
    {
        $supervisor = Supervisor::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'email' => 'required|email|unique:user_core_data,email,' . $core->id,
            'name' => 'required',
            'designation' => 'required',
        ]);

        if ($request->filled('password')) {
            $core->password = Hash::make($request->password);
        }

        $core->email = $request->email;
        $core->save();

        $supervisor->update($request->except('email', 'password'));

        return redirect()->route('admin.supervisors.index')->with('success', 'Supervisor updated successfully.');
    }

    public function deleteSupervisor($reg_no)
    {
        UserCoreData::where('reg_no', $reg_no)->delete();
        Supervisor::where('reg_no', $reg_no)->delete();
        return redirect()->route('admin.supervisors.index')->with('success', 'Supervisor deleted successfully.');
    }
    private function generateSupervisorRegNo()
    {
        $latest = \App\Models\Supervisor::orderBy('id', 'desc')->first();
        if (!$latest) {
            return 'SUP0001';
        }

        $number = (int) substr($latest->reg_no, 3) + 1;
        return 'SUP' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }


    // HOD Methods
    public function indexHods()
    {
        // Fetch HODs along with the related user_core_data (email, reg_no)
        $hods = Hod::with('userCoreData')->get();

        return view('admin.hods.index', compact('hods'));
    }

    public function createHod()
    {
        // fetch all departments
        $departments = Department::all();
        return view('admin.hods.create', compact('departments'));
    }

    public function storeHod(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|digits:5|unique:user_core_data,reg_no|unique:hod,reg_no',
            'name' => 'required|string',
            'email' => 'required|email|unique:user_core_data,email',
            'department' => 'required|string',
        ]);

        $password = Str::random(8);

        // Save to core user table
        UserCoreData::create([
            'reg_no' => $request->reg_no,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => 3,
        ]);

        // Save to HOD table
        Hod::create([
            'reg_no' => $request->reg_no,
            'name' => $request->name,
            'department' => $request->department,
            'description' => '',
        ]);

        // Email credentials
        Mail::to($request->email)->send(new HodAccountCreated(
            $request->name,
            $request->reg_no,
            $request->email,
            $password
        ));

        return redirect()->back()->with('success', "HOD added successfully.\n\nLogin Credentials:\nReg No: {$request->reg_no}\nEmail: {$request->email}\nPassword: {$password}");
    }

    public function editHod($id)
    {
        $hod = Hod::with('userCoreData')->findOrFail($id);
        $departments = Department::pluck('name', 'id');

        return view('admin.hods.edit', data: compact('hod', 'departments'));
    }


     public function updateHod(Request $request, $id)
    {
        $hod = Hod::findOrFail($id);
        $user = $hod->userCoreData;

        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:user_core_data,email,' . $user->id,
            'reg_no' => 'required|string|unique:user_core_data,reg_no,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update HOD table
        $hod->update([
            'name' => $request->name,
            'department' => $request->department,
        ]);

        // Update user_core_data table
        $user->email = $request->email;
        $user->reg_no = $request->reg_no;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.hods.index')->with('success', 'HOD updated successfully!');
    }


    public function deleteHod($id)
    {
        // Find and delete the HOD user
        $hod = \App\Models\UserCoreData::findOrFail($id); // Use UserCoreData here
        $hod->delete();

        return redirect()->route('admin.hods.index')->with('success', 'HOD deleted successfully.');
    }


    // ---------------------------
    // APPROVER 1 FUNCTIONS
    // ---------------------------

    public function indexApprover1()
    {
        $approvers = Approver1::all();
        return view('admin.approver1.index', compact('approvers'));
    }

    public function createApprover1()
    {
        $universities = University::all();
        return view('admin.approver1.create', compact('universities'));
    }

    public function storeApprover1(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|digits:5|unique:user_core_data,reg_no|unique:approver_1s,reg_no',
            'name' => 'required|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'university' => 'required|string',
            'email' => 'required|email|unique:user_core_data,email',
        ]);

        $password = Str::random(8);

        // Create UserCoreData
        UserCoreData::create([
            'reg_no' => $request->reg_no,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => 4,
        ]);

        // Create Approver1
        Approver1::create([
            'reg_no' => $request->reg_no,
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'university' => $request->university,
        ]);

        // Send email
        Mail::to($request->email)->send(new Approver1AccountCreated(
            $request->name,
            $request->reg_no,
            $request->email,
            $password
        ));

        return redirect()->route('admin.approver1.index')->with('success',
            "Approver 1 registered successfully.\n\nLogin Credentials:\nReg No: {$request->reg_no}\nEmail: {$request->email}\nPassword: {$password}"
        );
    }

        public function editApprover1($reg_no)
        {
            $approver = Approver1::where('reg_no', $reg_no)->firstOrFail();
            $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();
            $universities = University::all();

            return view('admin.approver1.edit', compact('approver', 'core', 'universities'));
        }


    public function updateApprover1(Request $request, $reg_no)
    {
        $approver = Approver1::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'university' => 'required|string',
            'email' => 'required|email|unique:user_core_data,email,' . $core->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $core->email = $request->email;
        if ($request->filled('password')) {
            $core->password = Hash::make($request->password);
        }
        $core->save();

        $approver->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'university' => $request->university,
        ]);

        return redirect()->route('admin.approver1.index')->with('success', 'Approver 1 updated successfully.');
    }

    public function deleteApprover1($reg_no)
    {
        UserCoreData::where('reg_no', $reg_no)->delete();
        Approver1::where('reg_no', $reg_no)->delete();
        return redirect()->route('admin.approver1.index')->with('success', 'Approver 1 deleted successfully.');
    }


    // ---------------------------
    // APPROVER 2 FUNCTIONS
    // ---------------------------

    public function indexApprover2()
    {
        $approvers = Approver2::all();
        return view('admin.approver2.index', compact('approvers'));
    }

    public function createApprover2()
    {
        $universities = University::all();
        return view('admin.approver2.create', compact('universities'));
    }

    public function storeApprover2(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|digits:5|unique:user_core_data,reg_no|unique:approver_2s,reg_no',
            'name' => 'required|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'university' => 'required|string',
            'email' => 'required|email|unique:user_core_data,email',
        ]);

        $password = Str::random(8);

        UserCoreData::create([
            'reg_no' => $request->reg_no,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => 5,
        ]);

        Approver2::create([
            'reg_no' => $request->reg_no,
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'university' => $request->university,
        ]);

        Mail::to($request->email)->send(new Approver2AccountCreated(
            $request->name,
            $request->reg_no,
            $request->email,
            $password
        ));

        return redirect()->route('admin.approver2.index')->with('success',
            "Approver 2 registered successfully.\n\nLogin Credentials:\nReg No: {$request->reg_no}\nEmail: {$request->email}\nPassword: {$password}"
        );
    }

    public function editApprover2($reg_no)
    {
        $approver = Approver2::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();
        $universities = University::all();

        return view('admin.approver2.edit', compact('approver', 'core','universities'));
    }

    public function updateApprover2(Request $request, $reg_no)
    {
        $approver = Approver2::where('reg_no', $reg_no)->firstOrFail();
        $core = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'university' => 'required|string',
            'email' => 'required|email|unique:user_core_data,email,' . $core->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $core->email = $request->email;
        if ($request->filled('password')) {
            $core->password = Hash::make($request->password);
        }
        $core->save();

        $approver->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.approver2.index')->with('success', 'Approver 2 updated successfully.');
    }

    public function deleteApprover2($reg_no)
    {
        // Delete core user data
        $core = UserCoreData::where('reg_no', $reg_no)->first();
        if ($core) {
            $core->delete();
        }

        // Delete from approver2 table
        $approver = Approver2::where('reg_no', $reg_no)->first();
        if ($approver) {
            $approver->delete();
        }

        return redirect()->route('admin.approver2.index')->with('success', 'Approver 2 deleted successfully.');
    }

    public function indexComplaints()
    {
        $intern = InternProfile::where('reg_no', auth()->user()->reg_no)->first();
        $complaints = Complaint::where('intern_reg_no', $intern->reg_no)->paginate(10);

        return view('interns.complain.index', compact('intern', 'complaints'));
    }
    public function promoteToSupervisor($reg_no)
    {
        $user = UserCoreData::where('reg_no', $reg_no)->first();
        $intern = InternProfile::where('reg_no', $reg_no)->first();

        if (!$user || !$intern) {
            return redirect()->back()->with('error', 'Intern not found.');
        }

        // Update role to Supervisor
        $user->role_id = 2;
        $user->save();

        // Insert into supervisor table with only required fields
        if (!Supervisor::where('reg_no', $reg_no)->exists()) {
            Supervisor::create([
                'reg_no' => $reg_no,
                'name' => $intern->name,
                'email' => $intern->email,
                'designation' => 'Promoted Intern',
            ]);
        }

        // Update intern profile status
        $intern->update([
            'is_active' => 0,
            'status' => 'Promoted to Supervisor',
        ]);

        return redirect()->back()->with('success', 'Intern successfully promoted to Supervisor. Internship record preserved.');
    }


    public function dashboardInternSearch(Request $request)
    {
        $search = $request->input('search');
        $interns = [];

        if ($search) {
            $interns = InternProfile::where('is_active', 1)
                ->where(function ($query) use ($search) {
                    $query->where('reg_no', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                })
                ->get();
        }

        return view('dashboards.admin', compact('search', 'interns'));
    }

    public function editCredentials($reg_no)
    {
        $user = UserCoreData::where('reg_no', $reg_no)->firstOrFail();
        return view('admin.users.edit_credentials', compact('user'));
    }

    public function updateEmail(Request $request, $reg_no)
    {
        $user = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'email' => 'required|email|unique:user_core_data,email,' . $user->id,
        ]);

        $user->email = $request->email;
        $user->save();

        return back()->with('email_success', 'Email updated successfully.');
    }

    public function updatePassword(Request $request, $reg_no)
    {
        $user = UserCoreData::where('reg_no', $reg_no)->firstOrFail();

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', 'Password updated successfully.');
    }


}
