<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Deduction::with('employee.department');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($empQ) use ($search) {
                    $empQ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                })->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        // Filter Jenis Potongan
        if ($request->filled('type') && $request->type != 'Semua') {
            $query->where('type', $request->type);
        }

        $deductions = $query->latest('date')->paginate(10)->withQueryString();

        // 📊 KALKULASI STATISTIK (BULAN INI)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyQuery = Deduction::whereMonth('date', $currentMonth)->whereYear('date', $currentYear);

        $totalNominal = (clone $monthlyQuery)->sum('amount');
        $totalKaryawan = (clone $monthlyQuery)->distinct('employee_id')->count('employee_id');
        $totalKasbon = (clone $monthlyQuery)->where('type', 'like', '%Kasbon%')->sum('amount');
        $totalDenda = (clone $monthlyQuery)->where('type', 'not like', '%Kasbon%')->sum('amount');

        return view('deductions.index', compact(
            'deductions',
            'totalNominal',
            'totalKaryawan',
            'totalKasbon',
            'totalDenda'
        ));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string',
        ]);

        Deduction::create($request->all());

        return redirect()->route('deductions.index')->with('success', 'Data potongan berhasil ditambahkan!');
    }

    public function edit(Deduction $deduction)
    {
        $employees = Employee::all();
        return view('deductions.edit', compact('deduction', 'employees'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string',
        ]);

        $deduction->update($request->all());

        return redirect()->route('deductions.index')->with('success', 'Data potongan berhasil diperbarui!');
    }

    public function destroy(Deduction $deduction)
    {
        $deduction->delete();

        return redirect()->route('deductions.index')->with('success', 'Data potongan berhasil dihapus!');
    }
}
