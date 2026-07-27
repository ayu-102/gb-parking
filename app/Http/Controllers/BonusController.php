<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $query = Bonus::with('employee');

        // Filter Pencarian (Nama, NIK, atau Judul Bonus)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('employee', function ($empQ) use ($request) {
                    $empQ->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('nik', 'like', '%' . $request->search . '%');
                })->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Jenis (Bonus / Insentif)
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        // Filter Periode (Bulan & Tahun, Default: Bulan Ini)
        $selectedPeriod = $request->input('period', Carbon::now()->format('Y-m'));
        if ($request->filled('period')) {
            $year  = Carbon::parse($selectedPeriod)->year;
            $month = Carbon::parse($selectedPeriod)->month;
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }

        // Data untuk Tabel Utama
        $bonuses = (clone $query)->latest('date')->paginate(10)->withQueryString();

        // 📊 STATISTIK CARDS (BERDASARKAN PERIODE TERPILIH)
        $statsQuery = Bonus::whereYear('date', Carbon::parse($selectedPeriod)->year)
            ->whereMonth('date', Carbon::parse($selectedPeriod)->month);

        $totalNominal  = (clone $statsQuery)->sum('amount');
        $totalPenerima = (clone $statsQuery)->distinct('employee_id')->count('employee_id');
        $totalBonus    = (clone $statsQuery)->where('type', 'bonus')->sum('amount');
        $totalInsentif = (clone $statsQuery)->where('type', 'incentive')->sum('amount');

        return view('bonuses.index', compact(
            'bonuses',
            'totalNominal',
            'totalPenerima',
            'totalBonus',
            'totalInsentif',
            'selectedPeriod'
        ));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('bonuses.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:bonus,incentive',
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string',
        ]);

        Bonus::create($request->all());

        return redirect()->route('bonuses.index')->with('success', 'Data bonus/insentif berhasil ditambahkan!');
    }

    public function edit(Bonus $bonus)
    {
        $employees = Employee::all();
        return view('bonuses.edit', compact('bonus', 'employees'));
    }

    public function update(Request $request, Bonus $bonus)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:bonus,incentive',
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string',
        ]);

        $bonus->update($request->all());

        return redirect()->route('bonuses.index')->with('success', 'Data bonus/insentif berhasil diperbarui!');
    }

    public function destroy(Bonus $bonus)
    {
        $bonus->delete();

        return redirect()->route('bonuses.index')->with('success', 'Data bonus/insentif berhasil dihapus!');
    }
}
