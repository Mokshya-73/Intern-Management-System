<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'supervisor';

    protected $fillable = ['reg_no', 'name', 'university', 'location', 'designation'];

    public function core()
    {
        return $this->belongsTo(UserCoreData::class, 'reg_no', 'reg_no');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'supervisor_reg_no', 'reg_no');
    }
    public function hods()
    {
        return $this->belongsToMany(Hod::class, 'hod_supervisors', 'supervisor_id', 'hod_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_supervisor', 'supervisor_id', 'department_id');
    }

    public function interns()
    {
        // Define the relationship through the intern_sessions table
        return $this->belongsToMany(InternProfile::class, 'intern_sessions', 'sup_id', 'reg_no');
    }

    public function internSessions()
    {
        return $this->hasMany(InternSession::class, 'sup_id', 'id');
    }



}
