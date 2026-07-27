<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Bonus;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        // Cuma tampilkan yang statusnya 'draft' atau 'rejected' (Yang 'approved' otomatis hilang dari sini!)
        $payrolls = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->whereIn('status', ['draft', 'rejected'])
            ->latest()
            ->paginate(10);

        return view('payrolls.index', compact('payrolls', 'selectedMonth'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month_year'  => 'required|string',
            'notes'       => 'nullable|string',
        ]);

        $employee = Employee::find($request->employee_id);
        $basicSalary = $employee->basic_salary ?? 0;

        // 1. Hitung Otomatis BPJS & Pajak Berdasarkan Persentase Gaji Pokok
        // (Bisa disesuaikan persentasenya, misal: BPJS 3%, Pajak PPh21 5%)
        $bpjsDeduction = $basicSalary * 0.03; // 3% dari Gaji Pokok
        $taxDeduction  = $basicSalary * 0.05; // 5% dari Gaji Pokok

        // 2. Ambil Potongan Kasbon / Lainnya dari Tabel 'deductions' (Misal: Kasbon Rp 50.000)
        $otherDeductions = 0;
        if (class_exists('\App\Models\Deduction')) {
            $otherDeductions = \App\Models\Deduction::where('employee_id', $employee->id)->sum('amount');
        }

        // 3. Ambil Potongan Komponen Gaji Tambahan (jika ada)
        $deductionFromComponent = \App\Models\SalaryComponent::where('type', 'deduction')->sum('amount');

        // TOTAL SEMUA POTONGAN (BPJS + Pajak + Kasbon/Potongan Lain)
        $totalDeduction = $bpjsDeduction + $taxDeduction + $otherDeductions + $deductionFromComponent;

        // 4. Hitung Pendapatan
        $totalBonus = Bonus::where('employee_id', $employee->id)
            ->where('date', 'like', $request->month_year . '%')
            ->sum('amount');

        $totalAllowance = \App\Models\SalaryComponent::where('type', 'allowance')->sum('amount');

        // 5. Gaji Bersih (Take Home Pay)
        $netSalary = ($basicSalary + $totalAllowance + $totalBonus) - $totalDeduction;

        Payroll::create([
            'employee_id'     => $employee->id,
            'month_year'      => $request->month_year,
            'basic_salary'    => $basicSalary,
            'total_allowance' => $totalAllowance,
            'total_bonus'     => $totalBonus,
            'total_deduction' => $totalDeduction,
            'net_salary'      => $netSalary,
            'status'          => 'draft',
            'notes'           => $request->notes,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll & Potongan BPJS/Pajak berhasil dipotong otomatis!');
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

        return redirect()->route('payrolls.index')->with('success', 'Catatan payroll berhasil diperbarui!');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Data draft payroll berhasil dihapus!');
    }

    public function approval(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        // Cuma tampilkan yang butuh keputusan: status 'draft'
        $payrolls = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'draft')
            ->latest()
            ->paginate(10);

        return view('payrolls.approval', compact('payrolls', 'selectedMonth'));
    }

    // 2. Aksi ACC / Approve
    public function approve(Payroll $payroll)
    {
        $payroll->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Payroll berhasil disetujui (Approved)!');
    }

    // 3. Aksi Tolak / Reject
    public function reject(Payroll $payroll)
    {
        $payroll->update(['status' => 'rejected']);

        return redirect()->back()->with('error', 'Payroll telah ditolak (Rejected).');
    }

    public function slip(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        // CUMA tampilkan yang SUDAH 'approved' (Di sini datanya bakal aman & tersimpan terus!)
        $payrolls = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('payrolls.slip', compact('payrolls', 'selectedMonth'));
    }

    public function printSlip(Payroll $payroll)
    {
        // Memastikan relasi karyawan terload
        $payroll->load('employee');

        return view('payrolls.print', compact('payroll'));
    }
}
