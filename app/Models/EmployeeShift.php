<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'shift_template_id', 'date', 'notes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftTemplate()
    {
        return $this->belongsTo(ShiftTemplate::class, 'shift_template_id');
    }
}
