<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    University,
    UniversityLocation,
    Department,
    Hod,
    Supervisor,
    DepartmentHod,
    HODSupervisor
};

class UniversityStructureController extends Controller
{
    public function index()
    {
        $universities = University::with([
            'locations.departments.departmentHod.hod',
            'locations.departments.departmentHod.supervisors'
        ])->get();


        return view('admin.structure.index', compact('universities'));
    }


    public function createUniversity()
    {
        return view('admin.structure.universities.create');
    }

    public function storeUniversity(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'short_name' => 'nullable',
            'type' => 'required|in:Public,Private,International',
            'established_year' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'website_url' => 'nullable|url',
        ]);

        University::create($validated);
        return redirect()->route('admin.universities.index')->with('success', 'University created');
    }

    public function createLocation($university_id)
    {
        return view('admin.structure.locations.create', compact('university_id'));
    }

    public function storeLocation(Request $request, $university_id)
    {
        $validated = $request->validate([
            'city' => 'required',
            'address' => 'required',
            'postcode' => 'nullable',
            'location_url' => 'nullable|url'
        ]);

        UniversityLocation::create(array_merge($validated, [
            'university_id' => $university_id
        ]));

        return redirect()->route('admin.universities.index')->with('success', 'Location added');
    }

    public function createDepartment(Request $request)
    {
        $universities = University::all();
        $selectedUniversity = $request->input('university_id');

        $locations = [];

        if ($selectedUniversity) {
            $locations = UniversityLocation::where('university_id', $selectedUniversity)->get();
        }

        return view('admin.structure.departments.create', compact('universities', 'locations', 'selectedUniversity'));
    }



    public function storeDepartment(Request $request)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id',
            'location_id' => 'required|exists:university_locations,id',
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('admin.universities.index')->with('success', 'Department created successfully.');
    }


    public function assignStructure(Request $request)
    {
        $universities = University::all();
        $hods = Hod::all();
        $supervisors = Supervisor::all();

        $locations = collect();
        $departments = collect();

        $selectedUniversity = $request->input('university_id');
        $selectedLocation = $request->input('location_id');
        $selectedDepartment = $request->input('department_id');

        if ($selectedUniversity) {
            $locations = UniversityLocation::where('university_id', $selectedUniversity)->get();
        }

        if ($selectedLocation) {
            $departments = Department::where('location_id', $selectedLocation)->get();
        }

        return view('admin.structure.assign', compact(
            'universities', 'locations', 'departments',
            'hods', 'supervisors',
            'selectedUniversity', 'selectedLocation', 'selectedDepartment'
        ));
    }

    public function storeStructure(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'hod_id' => 'required|exists:hod,id',
            'supervisor_ids' => 'required|array',
            'supervisor_ids.*' => 'exists:supervisor,id',
        ]);

        DepartmentHod::updateOrCreate(
            ['department_id' => $request->department_id],
            ['hod_id' => $request->hod_id, 'is_active' => 1]
        );

        HODSupervisor::where('hod_id', $request->hod_id)->update(['is_active' => 0]);

        foreach ($request->supervisor_ids as $supId) {
            HODSupervisor::create([
                'hod_id' => $request->hod_id,
                'supervisor_id' => $supId,
                'is_active' => 1
            ]);
        }

        return redirect()->route('admin.structure.assign')->with('success', 'Structure assigned successfully!');
    }

    public function showAssignHODForm(Request $request)
    {
        $universities = University::all();
        $locations = [];
        $departments = [];
        $hods = collect();

        if ($request->university_id) {
            $locations = UniversityLocation::where('university_id', $request->university_id)->get();
        }

        if ($request->university_id && $request->location_id) {
            $departments = Department::where('university_id', $request->university_id)
                                    ->where('location_id', $request->location_id)
                                    ->get();

            // 🔽 Change this
            $hods = HOD::all();

            // Debug log
            \Log::info('Assign HOD form', [
                'university_id' => $request->university_id,
                'location_id' => $request->location_id,
                'departments' => $departments->pluck('name', 'id'),
                'hods_count' => $hods->count(),
            ]);
        }

        return view('admin.structure.assign_hod', compact('universities', 'locations', 'departments', 'hods'));
    }






    public function storeAssignHOD(Request $request)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id',
            'location_id' => 'required|exists:university_locations,id',
            'department_id' => 'required|exists:departments,id',
            'hod_id' => 'required|exists:hod,id',
        ]);

        $existing = DepartmentHod::where('department_id', $request->department_id)
                    ->where('is_active', 1)
                    ->first();

        if ($existing) {
            return back()->with('error', 'This department already has an assigned HOD.');
        }

        // ✅ Assign HOD
        DepartmentHod::create([
            'department_id' => $request->department_id,
            'hod_id' => $request->hod_id,
            'is_active' => 1,
        ]);

        return back()->with('success', 'HOD assigned successfully.');
    }


    public function showAssignSupervisorForm(Request $request)
    {
        $universities = University::all();
        $locations = collect();
        $departments = collect();
        $selectedHod = null;
        $supervisors = collect();
        $assignedSupervisors = collect();

        if ($request->university_id) {
            $locations = UniversityLocation::where('university_id', $request->university_id)->get();
        }

        if ($request->university_id && $request->location_id) {
            $departments = Department::where([
                ['university_id', $request->university_id],
                ['location_id', $request->location_id],
            ])->get();
        }

        if ($request->department_id) {
            $selectedHodRecord = DepartmentHod::where([
                ['department_id', $request->department_id],
                ['is_active', 1],
            ])->first();

            $selectedHod = $selectedHodRecord?->hod;

            if ($selectedHod) {
                $assignedSupervisorIds = DB::table('hod_supervisors')
                    ->where('hod_id', $selectedHod->id)
                    ->where('is_active', 1)
                    ->pluck('supervisor_id');

                $assignedSupervisors = Supervisor::whereIn('id', $assignedSupervisorIds)->get();

                // Now get the university name
                $university = University::find($request->university_id);
                $universityName = $university?->name;

                $supervisors = Supervisor::where(function ($query) use ($universityName) {
                        $query->whereNull('university')
                            ->orWhere('university', $universityName);
                    })
                    ->whereNotIn('id', $assignedSupervisorIds) // Exclude already assigned ones
                    ->get();
            }
        }

        return view('admin.structure.assign_supervisor', compact(
            'universities',
            'locations',
            'departments',
            'selectedHod',
            'supervisors',
            'assignedSupervisors'
        ));
    }

    public function storeAssignSupervisor(Request $request)
    {
        $request->validate([
            'university_id'   => 'required|exists:universities,id',
            'location_id'     => 'required|exists:university_locations,id',
            'department_id'   => 'required|exists:departments,id',
            'supervisor_ids'  => 'required|array',
            'supervisor_ids.*'=> 'exists:supervisor,id',
        ]);

        // ✅ Get active HOD assigned to department
        $deptHod = DepartmentHod::where('department_id', $request->department_id)
                                ->where('is_active', 1)
                                ->first();

        if (!$deptHod) {
            return back()->with('error', 'Please assign a HOD to this department first.');
        }

        // ✅ Deactivate previous HOD-supervisor assignments for this HOD
        HODSupervisor::where('hod_id', $deptHod->hod_id)->update(['is_active' => 0]);

        // ✅ Assign new supervisors to the active HOD
        foreach ($request->supervisor_ids as $supId) {
            HODSupervisor::create([
                'hod_id'        => $deptHod->hod_id,
                'supervisor_id' => $supId,
                'is_active'     => 1,
            ]);
        }

        return redirect()->route('admin.structure.assign_supervisor')
                        ->with('success', 'Supervisors assigned successfully.');
    }



    public function removeHOD($department_id)
    {
        DepartmentHod::where('department_id', $department_id)->update(['is_active' => 0]);

        HODSupervisor::where('hod_id', function ($query) use ($department_id) {
            $query->select('hod_id')->from('department_hods')
                ->where('department_id', $department_id)->limit(1);
        })->update(['is_active' => 0]);

        return back()->with('success', 'HOD and associated Supervisors deactivated.');
    }

    public function removeSupervisor($supervisor_id)
    {
        HODSupervisor::where('supervisor_id', $supervisor_id)->update(['is_active' => 0]);
        return back()->with('success', 'Supervisor deactivated.');
    }

    public function viewStructure()
    {
        $universities = University::with([
            'locations.departments.departmentHod.hod'
        ])->get();


        return view('admin.structure.view_structure', compact('universities'));
    }
    
    public function changeHOD(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'new_hod_id' => 'required|exists:hod,id',
        ]);

        // Deactivate old HOD
        DepartmentHod::where('department_id', $request->department_id)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        // Insert new HOD
        $newDeptHod = DepartmentHod::create([
            'department_id' => $request->department_id,
            'hod_id' => $request->new_hod_id,
            'is_active' => 1
        ]);

        // Get previous HOD ID
        $oldHod = DepartmentHod::where('department_id', $request->department_id)
            ->orderByDesc('id')
            ->skip(1) // get the second most recent (the one we just deactivated)
            ->first();

        if ($oldHod) {
            // Reassign all supervisors from old HOD to new HOD
            $oldSupervisors = HODSupervisor::where('hod_id', $oldHod->hod_id)
                ->where('is_active', 1)
                ->get();

            foreach ($oldSupervisors as $sup) {
                // Deactivate old assignment
                $sup->update(['is_active' => 0]);

                // Create new assignment
                HODSupervisor::create([
                    'hod_id' => $request->new_hod_id,
                    'supervisor_id' => $sup->supervisor_id,
                    'is_active' => 1
                ]);
            }
        }

        return back()->with('success', 'HOD changed and supervisors reassigned.');
    }
public function getLocations($university_id)
{
    $locations = UniversityLocation::where('university_id', $university_id)
                    ->get(['id', 'city']);
    return response()->json($locations);
}

public function getDepartments($university_id, $location_id)
{
    $departments = Department::where('university_id', $university_id)
                    ->where('location_id', $location_id)
                    ->get(['id', 'name']);
    return response()->json($departments);
}
public function getSupervisorsByDepartment($department_id)
{
    // Get the active HOD for the department
    $deptHod = \App\Models\DepartmentHod::where('department_id', $department_id)
        ->where('is_active', 1)
        ->first();

    if (!$deptHod) {
        return response()->json([]);
    }

    // Get supervisor IDs assigned to the active HOD
    $supervisorIds = \App\Models\HODSupervisor::where('hod_id', $deptHod->hod_id)
        ->where('is_active', 1)
        ->pluck('supervisor_id');

    // Fetch supervisor records
    $supervisors = \App\Models\Supervisor::whereIn('id', $supervisorIds)->get(['id', 'name', 'reg_no']);

    return response()->json($supervisors);
}




}
