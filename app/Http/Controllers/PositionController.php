<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with('department')->withCount('employees');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhereHas('department', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        // Hitung Statistik ringkasan
        $totalPositions       = Position::count();
        $assignedDepartments  = Position::whereNotNull('department_id')->count();
        $unassigned           = Position::whereNull('department_id')->count();

        $positions = $query->latest()->paginate(10);

        return view('positions.index', compact(
            'positions',
            'totalPositions',
            'assignedDepartments',
            'unassigned'
        ));
    }

    public function create()
    {
        $departments = class_exists('\App\Models\Department') ? Department::all() : collect([]);
        return view('positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ], [
            'name.required' => 'Nama jabatan wajib diisi.',
        ]);

        Position::create($request->all());

        return redirect()->route('positions.index')->with('success', 'Jabatan baru berhasil ditambahkan!');
    }

    public function edit(Position $position)
    {
        $departments = class_exists('\App\Models\Department') ? Department::all() : collect([]);
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $position->update($request->all());

        return redirect()->route('positions.index')->with('success', 'Data jabatan berhasil diperbarui!');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Data jabatan berhasil dihapus!');
    }
}
