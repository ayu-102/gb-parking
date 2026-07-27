<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount('positions');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Hitung Ringkasan Statistik
        $totalDepartments = Department::count();
        $totalPositions   = class_exists('\App\Models\Position') ? Position::count() : 0;
        $avgPositions     = $totalDepartments > 0 ? round($totalPositions / $totalDepartments, 1) : 0;

        $departments = $query->latest()->paginate(10);

        return view('departments.index', compact(
            'departments',
            'totalDepartments',
            'totalPositions',
            'avgPositions'
        ));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:departments,code',
            'name' => 'required|string|max:255',
        ], [
            'code.required' => 'Kode departemen wajib diisi.',
            'code.unique'   => 'Kode departemen sudah digunakan.',
            'name.required' => 'Nama departemen wajib diisi.',
        ]);

        Department::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()->route('departments.index')->with('success', 'Departemen baru berhasil ditambahkan!');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
            'name' => 'required|string|max:255',
        ]);

        $department->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()->route('departments.index')->with('success', 'Data departemen berhasil diperbarui!');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Data departemen berhasil dihapus!');
    }
}
