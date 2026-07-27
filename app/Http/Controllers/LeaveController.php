<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * ==========================================
     * SISI KARYAWAN: Tampilan & Submit Cuti
     * ==========================================
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id ?? null)->first();

        if (!$employee) {
            return back()->with('error', 'Data karyawan belum terhubung.');
        }

        // Ambil riwayat pengajuan cuti/izin milik karyawan ini
        $leaves = Leave::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.leaves.index', compact('employee', 'leaves'));
    }

    public function employeeStore(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id ?? null)->first();

        $request->validate([
            'type'        => 'required|in:Cuti,Izin,Sakit',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:500',
            'attachment'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hitung total hari
        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Handle upload lampiran
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leaves', 'public');
        }

        Leave::create([
            'employee_id' => $employee->id,
            'type'        => $request->type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_days'  => $totalDays,
            'reason'      => $request->reason,
            'attachment'  => $attachmentPath,
            'status'      => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan ' . $request->type . ' berhasil dikirim, menunggu persetujuan HRD.');
    }

    /**
     * ==========================================
     * SISI ADMIN: Monitoring & Approval Cuti
     * ==========================================
     */
    public function index(Request $request)
    {
        $query = Leave::with('employee.department', 'employee.position');

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tipe
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();

        // Hitung Angka untuk Card Statistik
        $totalLeaves    = Leave::count();
        $pendingLeaves  = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::where('status', 'approved')->count();
        $rejectedLeaves = Leave::where('status', 'rejected')->count();

        return view('leaves.index', compact(
            'leaves',
            'totalLeaves',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves'
        ));
    }

    public function approve(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        // Cegah pemrosesan ganda jika sudah approved sebelumnya
        if ($leave->status === 'approved') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah disetujui sebelumnya.');
        }

        $leave->status = 'approved';
        $leave->save();

        // 1. POTONG JATAH/SISA CUTI KARYAWAN (Hanya jika tipe = 'Cuti')
        if ($leave->type === 'Cuti') {
            $employee = Employee::find($leave->employee_id);
            if ($employee) {
                // Mengurangi kolom sisa cuti (sesuai nama field kuota cuti di database kamu, misal: leave_quota)
                $totalDays = $leave->total_days ?? 1;

                // Mengurangi kuota cuti dan memastikan nilainya tidak negatif
                $employee->leave_quota = max(0, $employee->leave_quota - $totalDays);
                $employee->save();
            }
        }

        // 2. Inject / update data ke tabel Presences
        $startDate = Carbon::parse($leave->start_date);
        $endDate   = Carbon::parse($leave->end_date);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');

            $existingPresence = Presence::where('employee_id', $leave->employee_id)
                ->where('date', $formattedDate)
                ->first();

            if ($existingPresence && $existingPresence->time_in != null) {
                $existingPresence->update([
                    'status' => $leave->type,
                    'notes'  => ($existingPresence->notes ? $existingPresence->notes . ' | ' : '') . 'Izin Disetujui: ' . $leave->reason,
                ]);
            } else {
                Presence::updateOrCreate(
                    [
                        'employee_id' => $leave->employee_id,
                        'date'        => $formattedDate,
                    ],
                    [
                        'status'   => $leave->type,
                        'time_in'  => null,
                        'time_out' => null,
                        'notes'    => $leave->reason,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui, sisa cuti dipotong, dan masuk ke Rekap Absensi!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_note' => 'nullable|string|max:255', // Mengubah required menjadi nullable
        ]);

        $leave = Leave::findOrFail($id);
        $leave->status = 'rejected';
        $leave->rejection_note = $request->rejection_note ?? 'Ditolak oleh Admin';
        $leave->save();

        return redirect()->back()->with('success', 'Pengajuan telah berhasil ditolak.');
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);

        // Hapus file lampiran jika ada
        if ($leave->attachment && \Illuminate\Support\Facades\Storage::disk('public')->exists($leave->attachment)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($leave->attachment);
        }

        // Hapus data dari database
        $leave->delete();

        return redirect()->back()->with('success', 'Pengajuan berhasil dihapus!');
    }
}
