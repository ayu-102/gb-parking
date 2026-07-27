<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\EmployeeShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PresenceController extends Controller
{
    /**
     * Tampilan Utama Portal Absensi Karyawan
     */
    public function index()
    {
        $user = Auth::user();


        $employee = Employee::with(['location', 'position', 'department'])
            ->where('user_id', $user->id ?? null)
            ->first();

        if (!$employee) {
            return view('presence.index', [
                'employee'      => null,
                'todayPresence' => null,
                'message'       => 'Data karyawan belum terhubung ke akun login ini.'
            ]);
        }

        $today        = Carbon::today()->format('Y-m-d');
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;


        $todayPresence = Presence::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();


        $monthlyPresenceCount = Presence::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->whereNotNull('time_in')
            ->count();


        $lateCount = Presence::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Terlambat')
            ->count();


        $latestPayroll = Payroll::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->latest()
            ->first();


        $recentPresences = Presence::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();


        $todayEmployeeShift = EmployeeShift::with('shiftTemplate')
            ->where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        $shiftTemplate = optional($todayEmployeeShift)->shiftTemplate;

        $todayShift = [
            'name'       => optional($shiftTemplate)->name ?? 'Shift Regular',
            'start_time' => isset($shiftTemplate->start_time)
                ? Carbon::parse($shiftTemplate->start_time)->format('H:i')
                : '08:00',
            'end_time'   => isset($shiftTemplate->end_time)
                ? Carbon::parse($shiftTemplate->end_time)->format('H:i')
                : '17:00'
        ];

        return view('presence.index', compact(
            'employee',
            'todayPresence',
            'monthlyPresenceCount',
            'lateCount',
            'latestPayroll',
            'recentPresences',
            'todayShift'
        ));
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::with('location')->where('user_id', $user->id ?? null)->first();

        if (!$employee || !$employee->location) {
            return response()->json([
                'success' => false,
                'message' => 'Data lokasi penempatan Anda belum disetting oleh Admin!'
            ], 400);
        }

        $request->validate([
            'image' => 'required|string',
            'lat'   => 'required|numeric',
            'long'  => 'required|numeric',
            'type'  => 'required|in:in,out',
        ]);

        $loc = $employee->location;


        if (!$loc->latitude || !$loc->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat lokasi kerja belum diisi Admin di Google Maps!'
            ], 400);
        }

        // 1. HITUNG JARAK GPS (Haversine Formula)
        $distance = $this->calculateDistance(
            $request->lat,
            $request->long,
            $loc->latitude,
            $loc->longitude
        );

        $allowedRadius = $loc->radius ?? 100;

        if ($distance > $allowedRadius) {
            return response()->json([
                'success' => false,
                'message' => "Gagal Absen! Anda di luar radius. Jarak Anda: " . round($distance) . "m (Batas: {$allowedRadius}m)"
            ], 400);
        }

        // 2. PROSES UPLOAD FOTO SELFIE (Base64 ke Storage)
        try {
            $image = $request->image;


            if (str_contains($image, ';base64,')) {
                $imageParts  = explode(";base64,", $image);
                $imageBinary = base64_decode($imageParts[1]);
            } else {
                $imageBinary = base64_decode($image);
            }

            if (!$imageBinary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menguraikan data gambar (Base64 kosong)!'
                ], 400);
            }

            $filename  = 'selfie_' . $request->type . '_' . $employee->id . '_' . time() . '.jpg';
            $savedPath = 'presences/' . $filename;


            Storage::disk('public')->put($savedPath, $imageBinary);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan foto: ' . $e->getMessage()
            ], 400);
        }


        $today = Carbon::today()->format('Y-m-d');
        $now   = Carbon::now()->format('H:i:s');


        $todayEmployeeShift = EmployeeShift::with('shiftTemplate')
            ->where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();


        $shiftStartTime = optional(optional($todayEmployeeShift)->shiftTemplate)->start_time ?? '08:00:00';

        // 4. SIMPAN ATAU UPDATE KE DATABASE
        $presence = Presence::firstOrNew([
            'employee_id' => $employee->id,
            'date'        => $today,
        ]);

        if ($request->type === 'in') {
            if ($presence->time_in) {
                return response()->json(['success' => false, 'message' => 'Anda sudah Absen Masuk hari ini!'], 400);
            }

            $presence->time_in  = $now;
            $presence->photo_in = $savedPath;
            $presence->lat_in   = $request->lat;
            $presence->long_in  = $request->long;

            // OTOMATIS PENENTUAN STATUS (Hadir vs Terlambat)
            if ($now > $shiftStartTime) {
                $presence->status = 'Terlambat';
            } else {
                $presence->status = 'Hadir';
            }
        } else { // Absen Pulang ('out')
            if (!$presence->time_in) {
                return response()->json(['success' => false, 'message' => 'Anda belum Absen Masuk!'], 400);
            }
            if ($presence->time_out) {
                return response()->json(['success' => false, 'message' => 'Anda sudah Absen Pulang hari ini!'], 400);
            }

            $presence->time_out  = $now;
            $presence->photo_out = $savedPath;
            $presence->lat_out   = $request->lat;
            $presence->long_out  = $request->long;
        }

        $presence->save();

        $statusNote = ($request->type === 'in' && $presence->status === 'Terlambat') ? ' (Terlambat)' : '';

        return response()->json([
            'success' => true,
            'message' => 'Absen ' . ($request->type === 'in' ? 'Masuk' : 'Pulang') . ' Berhasil!' . $statusNote
        ]);
    }

    /**
     * Hitung Jarak 2 Titik GPS (Haversine Formula) dalam Meter
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function myPayrolls()
    {
        $user = Auth::user();

        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('presence.index')->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Ambil semua slip gaji milik karyawan ini yang statusnya approved
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->orderBy('month_year', 'desc')
            ->paginate(10);

        return view('presence.payrolls', compact('employee', 'payrolls'));
    }

    public function liveGps()
    {
        $user = Auth::user();

        $employee = Employee::with(['location', 'position', 'department'])
            ->where('user_id', $user->id ?? null)
            ->first();

        if (!$employee) {
            return view('presence.live_gps', [
                'employee'      => null,
                'todayPresence' => null,
                'message'       => 'Data karyawan belum terhubung ke akun login ini.'
            ]);
        }

        $today = Carbon::today()->format('Y-m-d');

        // Status Presensi Hari Ini
        $todayPresence = Presence::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        return view('presence.live_gps', compact('employee', 'todayPresence'));
    }
}
