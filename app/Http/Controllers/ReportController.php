<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // 1. Laporan Payroll (Utama)
    public function payroll(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
        $payrollType   = $request->get('payroll_type', 'Semua');
        $selectedDate  = $request->get('date');

        $query = Payroll::with('employee')
            ->where('status', 'approved');

        // Filter Tipe Payroll
        if ($payrollType === 'Bulanan') {
            $query->where('payroll_type', 'Bulanan')
                ->where('month_year', $selectedMonth);
        } elseif ($payrollType === 'Harian') {
            $query->where('payroll_type', 'Harian');
            if ($request->filled('date')) {
                $query->where('payroll_date', $selectedDate);
            } else {
                $query->where('month_year', $selectedMonth);
            }
        } else {
            // Semua Tipe (Bulanan & Harian) pada Bulan Berjalan
            $query->where('month_year', $selectedMonth);
        }

        // Calculation Ringkasan / Summary Cards
        $totalEmployees = (clone $query)->count();
        $totalIncome    = (clone $query)->sum('basic_salary') + (clone $query)->sum('total_allowance') + (clone $query)->sum('total_bonus');
        $totalDeduction = (clone $query)->sum('total_deduction');
        $totalTHP       = (clone $query)->sum('net_salary');

        $payrolls = $query->latest()->paginate(10);

        return view('reports.payroll', compact(
            'payrolls',
            'selectedMonth',
            'payrollType',
            'selectedDate',
            'totalEmployees',
            'totalIncome',
            'totalDeduction',
            'totalTHP'
        ));
    }

    // 2. Laporan Pajak (PPh 21)
    public function tax(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        // Pajak PPh21 hanya berlaku untuk karyawan bulanan
        $query = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('payroll_type', 'Bulanan')
            ->where('status', 'approved');

        $totalEmployees  = (clone $query)->count();

        $approvedPayrolls = (clone $query)->get();

        $totalGrossSalary = $approvedPayrolls->sum(function ($item) {
            return $item->basic_salary + $item->total_allowance + $item->total_bonus;
        });

        // PERBAIKAN DI SINI: Hitung langsung 5% dari Gaji Pokok (atau dari Gaji Bruto)
        $totalTax = (clone $query)->sum('basic_salary') * 0.05;

        $payrolls = $query->latest()->paginate(10);

        return view('reports.tax', compact(
            'payrolls',
            'selectedMonth',
            'totalEmployees',
            'totalGrossSalary',
            'totalTax'
        ));
    }

    // 3. Laporan BPJS
    public function bpjs(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));

        // BPJS hanya berlaku untuk karyawan bulanan
        $query = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('payroll_type', 'Bulanan')
            ->where('status', 'approved');

        $totalEmployees = (clone $query)->count();
        $totalBasic     = (clone $query)->sum('basic_salary');

        // PERBAIKAN DI SINI: Hitung langsung 3% dari total dasar gaji pokok
        $totalBpjs      = $totalBasic * 0.03;

        $payrolls = $query->latest()->paginate(10);

        return view('reports.bpjs', compact(
            'payrolls',
            'selectedMonth',
            'totalEmployees',
            'totalBasic',
            'totalBpjs'
        ));
    }
}
