<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'nik',
        'name',
        'email',
        'phone',
        'department_id',
        'position_id',
        'location_id',
        'basic_salary',
        'employee_type',
        'contract_end_date',
        'daily_rate',
        'status',
    ];

    protected $casts = [
        'contract_end_date' => 'date',
    ];

    // Relasi ke User Login
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    public function shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
