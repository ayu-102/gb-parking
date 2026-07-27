<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Location;
use App\Models\ShiftTemplate;
use App\Models\Employee;
use App\Models\EmployeeShift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin
        User::updateOrCreate(
            ['email' => 'admin@gbparking.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // 2. Data Dummy Departemen (DIBUAT PERTAMA)
        $deptOps = Department::firstOrCreate(
            ['code' => 'OPS'],
            ['name' => 'Operasional Parking']
        );

        $deptHrd = Department::firstOrCreate(
            ['code' => 'HRD'],
            ['name' => 'Human Resources']
        );

        // 3. Data Dummy Jabatan / Position (MENEMPEL KE DEPARTEMEN)
        $posSpv = Position::firstOrCreate(
            ['name' => 'Supervisor Lapangan'],
            ['department_id' => $deptHrd->id]
        );

        $posAtt = Position::firstOrCreate(
            ['name' => 'Petugas Parkir'],
            ['department_id' => $deptOps->id]
        );

        // 4. Data Dummy Lokasi + Latitude Longitude
        // 4. Data Dummy Lokasi + Kota, Radius, Latitude Longitude
        $locMall = Location::firstOrCreate(
            ['name' => 'GB Parking - Mall Grand Indonesia'],
            [
                'city'      => 'Jakarta Pusat',
                'radius'    => 100,
                'address'   => 'Jl. M.H. Thamrin No.1, Jakarta Pusat',
                'latitude'  => '-6.179232314427792',
                'longitude' => '106.67574339204354',
            ]
        );

        $locAirport = Location::firstOrCreate(
            ['name' => 'GB Parking - Bandara Soekarno Hatta T3'],
            [
                'city'      => 'Tangerang',
                'radius'    => 100,
                'address'   => 'Pajang, Kota Tangerang, Banten',
                'latitude'  => '-6.206246942799241',
                'longitude' => '106.67376052418963',
            ]
        );

        // 5. Template Shift (Pagi, Siang, Malam)
        $shiftPagi = ShiftTemplate::firstOrCreate(
            ['name' => 'Shift Pagi'],
            [
                'start_time'     => '07:00:00',
                'end_time'       => '15:00:00',
                'duration_hours' => 8,
                'is_active'      => true,
            ]
        );

        $shiftSiang = ShiftTemplate::firstOrCreate(
            ['name' => 'Shift Siang'],
            [
                'start_time'     => '15:00:00',
                'end_time'       => '23:00:00',
                'duration_hours' => 8,
                'is_active'      => true,
            ]
        );

        $shiftMalam = ShiftTemplate::firstOrCreate(
            ['name' => 'Shift Malam'],
            [
                'start_time'     => '23:00:00',
                'end_time'       => '07:00:00',
                'duration_hours' => 8,
                'is_active'      => true,
            ]
        );

        // 6. Data Dummy 2 Karyawan

        // Karyawan 1: Budi Santoso
        $userBudi = User::updateOrCreate(
            ['email' => 'budi@gbparking.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role'     => 'employee',
            ]
        );

        $empBudi = Employee::firstOrCreate(
            ['nik' => 'EMP-001'],
            [
                'user_id'       => $userBudi->id,
                'name'          => 'Budi Santoso',
                'email'         => 'budi@gbparking.com',
                'phone'         => '081234567890',
                'department_id' => $deptHrd->id,
                'position_id'   => $posSpv->id,
                'location_id'   => $locMall->id,
                'basic_salary'  => 4500000,
                'status'        => 'Aktif',
            ]
        );

        // Karyawan 2: Siti Rahma
        $userSaleh = User::updateOrCreate(
            ['email' => 'saleh@gbparking.com'],
            [
                'name'     => 'Salehudin',
                'password' => Hash::make('password123'),
                'role'     => 'employee',
            ]
        );

        $empSaleh = Employee::firstOrCreate(
            ['nik' => 'EMP-002'],
            [
                'user_id'       => $userSaleh->id,
                'name'          => 'Salehudin',
                'email'         => 'salehudin@gbparking.com',
                'phone'         => '089876543210',
                'department_id' => $deptOps->id,
                'position_id'   => $posAtt->id,
                'location_id'   => $locAirport->id,
                'basic_salary'  => 3800000,
                'status'        => 'Aktif',
            ]
        );

        // 7. Penempatan Jadwal Shift Karyawan (Hari Ini)
        EmployeeShift::firstOrCreate(
            [
                'employee_id' => $empBudi->id,
                'date'        => date('Y-m-d'),
            ],
            [
                'shift_template_id' => $shiftPagi->id,
                'notes'             => 'Shift Reguler Pagi',
            ]
        );

        EmployeeShift::firstOrCreate(
            [
                'employee_id' => $empSaleh->id,
                'date'        => date('Y-m-d'),
            ],
            [
                'shift_template_id' => $shiftMalam->id,
                'notes'             => 'Shift Reguler Malam',
            ]
        );
    }
}
