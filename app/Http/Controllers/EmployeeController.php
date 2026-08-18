<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['location', 'position', 'user']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->latest()->paginate(10);

        $totalTetap = Employee::where('employee_type', 'Tetap')->count();
        $totalKontrak = Employee::where('employee_type', 'Kontrak')->count();
        $totalHarian = Employee::where('employee_type', 'Harian')->count();
        $totalBaru = Employee::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('employees.index', compact('employees', 'totalTetap', 'totalKontrak', 'totalHarian', 'totalBaru'));
    }

    public function create()
    {
        $departments = Department::all();
        $locations = Location::all();
        $positions = Position::all();
        return view('employees.create', compact('departments', 'locations', 'positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'               => 'required|unique:employees,nik',
            'name'              => 'required|string|max:255',
            'employee_type'     => 'required|in:Tetap,Kontrak,Harian',
            'contract_end_date' => 'nullable|required_if:employee_type,Kontrak|date',
            'email'             => 'required|email|unique:users,email|unique:employees,email',
            'phone'             => 'nullable|string|max:20',
            'location_id'       => 'nullable',
            'department_id'     => 'nullable|exists:departments,id',
            'position_id'       => 'required',
            'basic_salary'      => 'required|numeric',
            'status'            => 'required|in:Aktif,Nonaktif',
            'password'          => 'required|min:6',
        ], [
            'nik.required'                  => 'NIK wajib diisi.',
            'nik.unique'                    => 'NIK sudah digunakan.',
            'name.required'                 => 'Nama karyawan wajib diisi.',
            'contract_end_date.required_if' => 'Tanggal berakhir kontrak wajib diisi untuk karyawan Kontrak.',
            'email.required'                => 'Email wajib diisi untuk akun login karyawan.',
            'email.unique'                  => 'Email sudah terdaftar.',
            'password.required'             => 'Password default wajib diisi.',
            'password.min'                  => 'Password minimal 6 karakter.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'karyawan',
            ]);

            Employee::create([
                'user_id'           => $user->id,
                'nik'               => $request->nik,
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'location_id'       => $request->location_id,
                'department_id'     => $request->department_id,
                'position_id'       => $request->position_id,
                'basic_salary'      => $request->basic_salary,
                'employee_type'     => $request->employee_type,
                'contract_end_date' => $request->employee_type === 'Kontrak' ? $request->contract_end_date : null,
                'status'            => $request->status,
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Karyawan dan Akun Login berhasil dibuat!');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::all();
        $locations = Location::all();
        $positions = Position::all();

        return view('employees.edit', compact('employee', 'departments', 'locations', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nik'               => 'required|unique:employees,nik,' . $employee->id,
            'name'              => 'required|string|max:255',
            'employee_type'     => 'required|in:Tetap,Kontrak,Harian',
            'contract_end_date' => 'nullable|required_if:employee_type,Kontrak|date',
            'email'             => 'required|email|unique:employees,email,' . $employee->id,
            'phone'             => 'nullable|string|max:20',
            'location_id'       => 'nullable',
            'department_id'     => 'nullable|exists:departments,id',
            'position_id'       => 'required',
            'basic_salary'      => 'required|numeric',
            'status'            => 'required|in:Aktif,Nonaktif',
        ]);

        DB::transaction(function () use ($request, $employee) {
            $data = $request->all();
            if ($request->employee_type !== 'Kontrak') {
                $data['contract_end_date'] = null;
            }

            $employee->update($data);

            if ($employee->user) {
                $employee->user->update([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            if ($employee->user) {
                $employee->user->delete();
            }
            $employee->delete();
        });

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
