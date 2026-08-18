<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Deduction;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_type',
        'month_year',
        'payroll_date',
        'basic_salary',
        'total_allowance',
        'total_bonus',
        'total_deduction',
        'net_salary',
        'status',
        'notes',
    ];

    // 1. Ambil potongan khusus Pajak / PPh
    public function getTaxDeductionAttribute()
    {
        return Deduction::where('employee_id', $this->employee_id)
            ->whereMonth('date', substr($this->month_year, 5, 2))
            ->whereYear('date', substr($this->month_year, 0, 4))
            ->where(function ($q) {
                $q->where('type', 'like', '%Pajak%')
                    ->orWhere('type', 'like', '%PPh%');
            })
            ->sum('amount') ?? 0;
    }

    // 2. Ambil potongan khusus BPJS
    public function getBpjsDeductionAttribute()
    {
        return Deduction::where('employee_id', $this->employee_id)
            ->whereMonth('date', substr($this->month_year, 5, 2))
            ->whereYear('date', substr($this->month_year, 0, 4))
            ->where('type', 'like', '%BPJS%')
            ->sum('amount') ?? 0;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
