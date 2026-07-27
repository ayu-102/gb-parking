<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\Employee;
use App\Models\ShiftTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeShift::with(['employee', 'shiftTemplate']);

        // Search Filter (Nama atau NIK)
        if ($request->filled('search')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        // Tanggal Filter (Default: Hari ini jika tidak diset)
        $filterDate = $request->input('date', date('Y-m-d'));

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        // Filter berdasarkan Shift Template jika ada
        if ($request->filled('shift_id') && $request->shift_id != 'Semua') {
            $query->where('shift_template_id', $request->shift_id);
        }

        $schedules = $query->latest('date')->paginate(10)->withQueryString();

        //  STATISTIK DINAMIS SESUAI FILTER TANGGAL
        $statsQuery = EmployeeShift::where('date', $filterDate);

        $totalShiftAktif = ShiftTemplate::where('is_active', true)->count();
        $totalTerjadwal  = (clone $statsQuery)->count();

        // Hitung Karyawan per Shift (Pagi, Siang, Malam)
        $shiftPagi  = (clone $statsQuery)->whereHas('shiftTemplate', fn($q) => $q->where('name', 'like', '%Pagi%'))->count();
        $shiftSiang = (clone $statsQuery)->whereHas('shiftTemplate', fn($q) => $q->where('name', 'like', '%Siang%'))->count();
        $shiftMalam = (clone $statsQuery)->whereHas('shiftTemplate', fn($q) => $q->where('name', 'like', '%Malam%'))->count();

        $shiftTemplates = ShiftTemplate::where('is_active', true)->get();

        //  AMBIL MODEL TEMPLATE UNTUK TAMPILKAN JAM DINAMIS DI CARD
        $templatePagi  = $shiftTemplates->firstWhere('name', 'Shift Pagi');
        $templateSiang = $shiftTemplates->firstWhere('name', 'Shift Siang');
        $templateMalam = $shiftTemplates->firstWhere('name', 'Shift Malam');

        return view('employee_shifts.index', compact(
            'schedules',
            'shiftTemplates',
            'totalShiftAktif',
            'totalTerjadwal',
            'shiftPagi',
            'shiftSiang',
            'shiftMalam',
            'filterDate',
            'templatePagi',
            'templateSiang',
            'templateMalam'
        ));
    }

    public function create()
    {
        $employees = Employee::all();
        $shiftTemplates = ShiftTemplate::where('is_active', true)->get();
        return view('employee_shifts.create', compact('employees', 'shiftTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_ids'      => 'required|array|min:1',
            'employee_ids.*'    => 'exists:employees,id',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'shift_template_id' => 'required|exists:shift_templates,id',
            'notes'             => 'nullable|string',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $employees = $request->employee_ids;

        $createdCount = 0;

        // Loop setiap karyawan yang dipilih
        foreach ($employees as $employeeId) {
            // Loop dari start_date sampai end_date
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $dateString = $currentDate->format('Y-m-d');

                // Mencegah duplikat: jika sudah ada di tanggal tersebut, update shift-nya
                EmployeeShift::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date'        => $dateString,
                    ],
                    [
                        'shift_template_id' => $request->shift_template_id,
                        'notes'             => $request->notes,
                    ]
                );

                $createdCount++;
                $currentDate->addDay();
            }
        }

        return redirect()->route('employee-shifts.index')
            ->with('success', "Berhasil menambahkan/memperbarui {$createdCount} jadwal shift!");
    }

    public function edit(EmployeeShift $employeeShift)
    {
        $employees = Employee::all();
        $shiftTemplates = ShiftTemplate::where('is_active', true)->get();
        return view('employee_shifts.edit', compact('employeeShift', 'employees', 'shiftTemplates'));
    }

    public function update(Request $request, EmployeeShift $employeeShift)
    {
        $request->validate([
            'employee_id'       => 'required|exists:employees,id',
            'shift_template_id' => 'required|exists:shift_templates,id',
            'date'              => 'required|date',
            'notes'             => 'nullable|string',
        ]);

        $employeeShift->update($request->all());

        return redirect()->route('employee-shifts.index')->with('success', 'Jadwal shift berhasil diperbarui!');
    }

    public function destroy(EmployeeShift $employeeShift)
    {
        $employeeShift->delete();

        return redirect()->route('employee-shifts.index')->with('success', 'Jadwal shift berhasil dihapus!');
    }
}
