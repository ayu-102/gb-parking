<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'photo_in',
        'lat_in',
        'long_in',
        'time_out',
        'photo_out',
        'lat_out',
        'long_out',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
