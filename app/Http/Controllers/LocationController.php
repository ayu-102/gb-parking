<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Employee;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::withCount('employees');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%');
        }

        $totalLocations = Location::count();
        $totalCities    = Location::whereNotNull('city')->where('city', '!=', '')->distinct('city')->count('city');
        $totalEmployees = class_exists('\App\Models\Employee') ? Employee::count() : 0;

        $locations = $query->latest()->paginate(10);

        return view('locations.index', compact(
            'locations',
            'totalLocations',
            'totalCities',
            'totalEmployees'
        ));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'city'      => 'nullable|string|max:255',
            'address'   => 'nullable|string',
            'radius'    => 'required|numeric|min:1',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'name.required'   => 'Nama lokasi wajib diisi.',
            'radius.required' => 'Radius absensi wajib diisi.',
        ]);

        Location::create($request->all());

        return redirect()->route('locations.index')->with('success', 'Lokasi baru berhasil ditambahkan!');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'city'      => 'nullable|string|max:255',
            'address'   => 'nullable|string',
            'radius'    => 'required|numeric|min:1',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Data lokasi berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Data lokasi berhasil dihapus!');
    }
}
