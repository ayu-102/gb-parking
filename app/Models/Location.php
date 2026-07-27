<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'radius',
        'latitude',
        'longitude',
    ];

    // Relasi: Satu lokasi punya banyak karyawan
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
