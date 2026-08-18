<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Bonus;
use App\Models\Presence; // Sesuaikan jika nama model absensi Anda berbeda
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
        $payrollType   = $request->get('payroll_type', 'Bulanan');
        $selectedDate  = $request->get('date');

        $query = Payroll::with('employee')
            ->whereIn('status', ['draft', 'rejected']);

        // Filter Tipe Penggajian
        if ($payrollType === 'Harian') {
            $query->where('payroll_type', 'Harian');

            // Jika user memilih tanggal spesifik
            if ($request->filled('date')) {
                $query->where('payroll_date', $selectedDate);
            } else {
                // Tampilkan semua payroll harian yang terjadi pada bulan & tahun terpilih
                $query->where(function ($q) use ($selectedMonth) {
                    $q->where('month_year', $selectedMonth)
                        ->orWhere('payroll_date', 'like', $selectedMonth . '%');
                });
            }
        } else {
            $query->where('payroll_type', 'Bulanan')
                ->where('month_year', $selectedMonth);
        }

        $payrolls = $query->latest()->paginate(10);

        return view('payrolls.index', compact('payrolls', 'selectedMonth', 'payrollType', 'selectedDate'));
    }

    public function create()
    {
        // Tarik semua karyawan aktif untuk dropdown
        $employees = Employee::where('status', 'Aktif')->get();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'payroll_type' => 'required|in:Bulanan,Harian',
            'month_year'   => 'nullable|string',
            'payroll_date' => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $basicSalary = $employee->basic_salary ?? 0;

        // Pakai langsung tipe yang dikirim dari form
        $payrollType = $request->payroll_type;

        if ($payrollType === 'Harian') {
            $bpjsDeduction = 0;
            $taxDeduction  = 0;

            $payrollDate = $request->payroll_date ?? date('Y-m-d');
            $monthYear   = date('Y-m', strtotime($payrollDate));

            $totalBonus = Bonus::where('employee_id', $employee->id)
                ->whereDate('date', $payrollDate)
                ->sum('amount');
        } else {
            $bpjsDeduction = $basicSalary * 0.03;
            $taxDeduction  = $basicSalary * 0.05;

            $monthYear   = $request->month_year ?? date('Y-m');
            $payrollDate = null;

            $totalBonus = Bonus::where('employee_id', $employee->id)
                ->where('month_year', $monthYear)
                ->sum('amount');
        }

        $totalDeduction = $bpjsDeduction + $taxDeduction;
        $netSalary      = ($basicSalary + $totalBonus) - $totalDeduction;

        Payroll::create([
            'employee_id'     => $employee->id,
            'payroll_type'    => $payrollType, // Menyimpan Harian/Bulanan sesuai request
            'month_year'      => $monthYear,
            'payroll_date'    => $payrollDate,
            'basic_salary'    => $basicSalary,
            'total_bonus'     => $totalBonus,
            'bpjs_deduction'  => $bpjsDeduction,
            'tax_deduction'   => $taxDeduction,
            'total_deduction' => $totalDeduction,
            'net_salary'      => $netSalary,
            'status'          => 'draft',
            'notes'           => $request->notes,
        ]);

        return redirect()->route('payrolls.index', [
            'payroll_type' => $payrollType,
            'month'        => $monthYear
        ])->with('success', 'Draft Payroll berhasil dibuat!');
    }

    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $payroll->update([
            'notes' => $request->notes,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Catatan Payroll berhasil diperbarui!');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->back()->with('success', 'Draft Payroll berhasil dihapus!');
    }

    public function approval(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        $payrolls = Payroll::with('employee')
            ->where('status', 'draft')
            ->latest()
            ->paginate(10);

        return view('payrolls.approval', compact('payrolls', 'selectedMonth'));
    }

    public function approve(Payroll $payroll)
    {
        $payroll->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Payroll berhasil disetujui (Approved)!');
    }

    public function reject(Payroll $payroll)
    {
        $payroll->update(['status' => 'rejected']);
        return redirect()->back()->with('error', 'Payroll telah ditolak (Rejected).');
    }

    public function slip(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        $payrolls = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('payrolls.slip', compact('payrolls', 'selectedMonth'));
    }

    public function printSlip(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('payrolls.print', compact('payroll'));
    }
}
