<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Default Range (Awal Bulan s/d Hari Ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // 2. Query Utama berdasarkan Rentang Tanggal (whereBetween)
        $query = Presence::with('employee')
            ->whereBetween('date', [$startDate, $endDate]);

        // Filter Pencarian (Nama atau NIK Karyawan)
        if ($request->filled('search')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status untuk Tabel
        if ($request->filled('status') && !in_array($request->status, ['Semua', 'Semua Status'])) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->latest('id')->paginate(15)->withQueryString();

        // 3. STATISTIK DINAMIS (Sesuai Rentang Tanggal yang Dipilih)
        $statsQuery = Presence::whereBetween('date', [$startDate, $endDate]);

        // Menghitung Hadir
        $totalHadir = (clone $statsQuery)->where(function ($q) {
            $q->where('status', 'Hadir')
                ->orWhere('status', 'present')
                ->orWhere('status', 'Tepat Waktu');
        })->count();

        // Menghitung Terlambat
        $totalTerlambat = (clone $statsQuery)->where(function ($q) {
            $q->where('status', 'Terlambat')
                ->orWhere('status', 'late');
        })->count();

        // Menghitung Izin & Sakit
        $totalIzinSakit = (clone $statsQuery)->whereIn('status', ['Izin', 'Sakit', 'permit', 'sick'])->count();

        // Menghitung Alpha
        $totalAlpha = (clone $statsQuery)->whereIn('status', ['Alpha', 'absent'])->count();

        return view('attendances.index', compact(
            'attendances',
            'totalHadir',
            'totalTerlambat',
            'totalIzinSakit',
            'totalAlpha',
            'startDate',
            'endDate'
        ));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'status'      => 'required|string',
            'time_in'     => 'nullable',
            'time_out'    => 'nullable',
            'notes'       => 'nullable|string',
        ]);

        Presence::create($request->all());

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dicatat!');
    }

    public function edit(Presence $attendance)
    {
        $employees = Employee::all();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Presence $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'status'      => 'required|string',
            'time_in'     => 'nullable',
            'time_out'    => 'nullable',
            'notes'       => 'nullable|string',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil diperbarui!');
    }

    public function destroy(Presence $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus!');
    }
}
