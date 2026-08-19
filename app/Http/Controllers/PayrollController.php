<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Bonus;
use App\Models\SalaryComponent; // Model komponen gaji global
use Illuminate\Http\Request;
use Carbon\Carbon;

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

            if ($request->filled('date')) {
                $query->where('payroll_date', $selectedDate);
            } else {
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

        $employee    = Employee::findOrFail($request->employee_id);
        $basicSalary = $employee->basic_salary ?? 0;
        $payrollType = $request->payroll_type;

        if ($payrollType === 'Harian') {
            // === OPSI A: KARYAWAN HARIAN ===
            // Potongan BPJS & Pajak Harian diset 0
            $bpjsDeduction  = 0;
            $taxDeduction   = 0;
            $totalAllowance = 0; // Karyawan harian tidak menerima komponen tunjangan rutin bulanan
            $extraDeduction = 0; // Karyawan harian tidak terkena potongan komponen rutin bulanan

            $payrollDate = $request->payroll_date ?? date('Y-m-d');
            $monthYear   = date('Y-m', strtotime($payrollDate));

            // Perhitungan Bonus khusus tanggal tersebut
            $totalBonus = Bonus::where('employee_id', $employee->id)
                ->whereDate('date', $payrollDate)
                ->sum('amount');
        } else {
            // === OPSI B: KARYAWAN BULANAN ===
            $monthYear   = $request->month_year ?? date('Y-m');
            $payrollDate = null;
            $parsedDate  = Carbon::parse($monthYear . '-01');

            // 1. Potongan Spesifik Standar (BPJS & Pajak)
            $bpjsDeduction = $basicSalary * 0.03;
            $taxDeduction  = $basicSalary * 0.05;

            // 2. Bonus Spesifik Karyawan pada bulan tersebut
            $totalBonus = Bonus::where('employee_id', $employee->id)
                ->whereYear('date', $parsedDate->year)
                ->whereMonth('date', $parsedDate->month)
                ->sum('amount');

            // 3. HITUNG KOMPONEN GAJI GLOBAL (Master SalaryComponents)
            $salaryComponents = SalaryComponent::all();

            $totalAllowance = 0;
            $extraDeduction = 0;

            foreach ($salaryComponents as $component) {
                // Hitung Nominal berdasarkan tipe (Fixed / Percentage)
                $value = 0;
                if ($component->amount_type === 'percentage') {
                    $value = ($basicSalary * $component->amount) / 100;
                } else {
                    $value = $component->amount;
                }

                // Kelompokkan ke Tunjangan (Allowance) atau Potongan (Deduction)
                if ($component->type === 'allowance') {
                    $totalAllowance += $value;
                } elseif ($component->type === 'deduction') {
                    $extraDeduction += $value;
                }
            }
        }

        // Total Potongan = (BPJS + Pajak) + Potongan Komponen Global
        $totalDeduction = $bpjsDeduction + $taxDeduction + $extraDeduction;

        // Take Home Pay (THP) = Gaji Pokok + Total Tunjangan + Total Bonus - Total Potongan
        $netSalary = ($basicSalary + $totalAllowance + $totalBonus) - $totalDeduction;

        Payroll::create([
            'employee_id'     => $employee->id,
            'payroll_type'    => $payrollType,
            'month_year'      => $monthYear,
            'payroll_date'    => $payrollDate,
            'basic_salary'    => $basicSalary,
            'total_allowance' => $totalAllowance, // <-- DIPISAH: Tersimpan murni sebagai Tunjangan
            'total_bonus'     => $totalBonus,     // <-- DIPISAH: Tersimpan murni sebagai Bonus
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

        return redirect()->route('payrolls.index', [
            'month'        => $payroll->month_year,
            'payroll_type' => $payroll->payroll_type
        ])->with('success', 'Catatan Payroll berhasil diperbarui!');
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
