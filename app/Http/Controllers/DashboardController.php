<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = strtolower(trim($user->role ?? ''));

        // KHUSUS KARYAWAN: Arahkan ke Presensi/Dashboard Karyawan
        if ($user->email !== 'admin@gbparking.com' && ($userRole === 'employee' || $userRole === 'karyawan')) {
            return redirect()->route('presence.index');
        }

        // -------------------------------------------------------------
        // DASHBOARD ADMIN (Untuk Admin / Superadmin)
        // -------------------------------------------------------------

        // 1. Data Stat Cards (Admin)
        $totalKaryawan = Employee::count();
        $totalLokasi   = Location::count();

        $totalPayrollBulanIni = Payroll::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('net_salary');

        // FIX ISSUE 1: Cek status 'draft' ATAU 'pending' agar terdeteksi presisi
        $pendingApproval = Payroll::whereIn('status', ['draft', 'pending', 'Draft', 'Pending'])->count();

        // 2. Data Filter Tahun
        $selectedYear = $request->input('year', date('Y'));

        // 3. Array Label Bulan
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData   = [];

        // 4. Hitung Total Gaji dari Bulan 1 s/d 12
        for ($m = 1; $m <= 12; $m++) {
            $sumGaji = Payroll::whereMonth('created_at', $m)
                ->whereYear('created_at', $selectedYear)
                ->sum('net_salary');

            // FIX ISSUE 2: Kirim angka nominal asli (misal: 4600000), bukan desimal 4.6
            $chartData[] = (float) $sumGaji;
        }

        return view('dashboard', compact(
            'totalKaryawan',
            'totalLokasi',
            'totalPayrollBulanIni',
            'pendingApproval',
            'chartLabels',
            'chartData',
            'selectedYear'
        ));
    }
}
