<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    // Relasi ke Position
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
