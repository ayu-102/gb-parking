<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'rejection_note',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
