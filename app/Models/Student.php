<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\StudentClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
