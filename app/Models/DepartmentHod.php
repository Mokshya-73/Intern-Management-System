<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHod extends Model
{
    protected $table = 'department_hods'; 
    protected $fillable = ['department_id', 'hod_id', 'is_active', 'removal_reason'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function hod()
    {
        return $this->belongsTo(Hod::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'hod_supervisors', 'hod_id', 'supervisor_id');
    }

}
