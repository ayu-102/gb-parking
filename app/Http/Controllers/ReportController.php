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


        $query = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'approved');


        $totalEmployees = (clone $query)->count();
        $totalIncome    = (clone $query)->sum('basic_salary') + (clone $query)->sum('total_allowance') + (clone $query)->sum('total_bonus');
        $totalDeduction = (clone $query)->sum('total_deduction');
        $totalTHP       = (clone $query)->sum('net_salary');


        $payrolls = $query->latest()->paginate(10);

        return view('reports.payroll', compact(
            'payrolls',
            'selectedMonth',
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


        $query = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'approved');


        $totalEmployees = (clone $query)->count();

        // Perhitungan Total Pendapatan Bruto (Gaji Pokok + Tunjangan + Bonus)
        $totalGrossSalary = (clone $query)->get()->sum(function ($item) {
            return $item->basic_salary + $item->total_allowance + $item->total_bonus;
        });

        // Perhitungan Total Estimasi Pajak PPh 21 (5% dari Gaji Pokok)
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


        $query = Payroll::with('employee')
            ->where('month_year', $selectedMonth)
            ->where('status', 'approved');


        $totalEmployees = (clone $query)->count();
        $totalBasic     = (clone $query)->sum('basic_salary');
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
