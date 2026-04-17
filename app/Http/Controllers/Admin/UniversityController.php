<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unis;
use App\Models\UniLoc;

class UniversityController extends Controller
{
    // Show the create form with all universities and their locations
    public function create()
    {
        $universities = Unis::with('loc')->get(); // Eager load locations
        return view('admin.unis.create', compact('universities'));
    }

    // Store new university
    public function store(Request $request)
    {
        $request->validate([
            'uni_name' => 'required|string|max:255|unique:unis,uni_name',
        ]);

        Unis::create(['uni_name' => $request->uni_name]);

        return redirect()->route('admin.unis.create')->with('success', 'University created.');
    }

    // Store new location
    public function storeLocation(Request $request)
    {
        $request->validate([
            'uni_id' => 'required|exists:unis,id',
            'location' => 'required|string|max:255',
        ]);

        UniLoc::create([
            'uni_id' => $request->uni_id,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.unis.create')->with('success', 'Location added successfully.');
    }

    // Edit university
    public function edit($id)
    {
        $university = Unis::findOrFail($id);
        return view('admin.unis.edit', compact('university'));
    }

    // Update university
    public function update(Request $request, $id)
    {
        $request->validate([
            'uni_name' => 'required|string|max:255|unique:unis,uni_name,' . $id,
        ]);

        $university = Unis::findOrFail($id);
        $university->update(['uni_name' => $request->uni_name]);

        return redirect()->route('admin.unis.create')->with('success', 'University updated.');
    }

    // Delete university
    public function destroy($id)
    {
        $university = Unis::findOrFail($id);
        $university->loc()->delete(); // Delete related locations first
        $university->delete();

        return redirect()->route('admin.unis.create')->with('success', 'University deleted.');
    }

    // Edit location
    public function editLocation($id)
    {
        $location = UniLoc::findOrFail($id);
        $universities = Unis::all();

        return view('admin.locs.edit', compact('location', 'universities'));
    }

    // Update location
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'uni_id' => 'required|exists:unis,id',
            'location' => 'required|string|max:255',
        ]);

        $location = UniLoc::findOrFail($id);
        $location->update([
            'uni_id' => $request->uni_id,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.unis.create')->with('success', 'Location updated.');
    }

    // Delete location
    public function destroyLocation($id)
    {
        $location = UniLoc::findOrFail($id);
        $location->delete();

        return redirect()->route('admin.unis.create')->with('success', 'Location deleted.');
    }


}
