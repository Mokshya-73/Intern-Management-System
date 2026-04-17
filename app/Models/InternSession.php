<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternSession extends Model
{
    protected $fillable = [
        'reg_no', 'session_id', 'sup_id', 'uni_id', 'department_id',
        'location', 'project_name', 'project_path',
        'supervisor_feedback', 'is_approved','hod_id', 'hod_approved'
    ];

    public function session()
    {
        return $this->belongsTo(ISession::class, 'session_id');
    }

    public function tasks()
    {
        return $this->hasMany(SessionTask::class, 'intern_session_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'sup_id', 'id');
    }

    public function intern()
    {
        return $this->belongsTo(InternProfile::class, 'reg_no', 'reg_no');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'intern_reg_no', 'reg_no');
    }
    public function hod()
    {
        return $this->belongsTo(Hod::class, 'hod_id');
    }
    public function universityLocation()
    {
        return $this->belongsTo(\App\Models\UniversityLocation::class, 'location');
    }
    public function internComplaints()
    {
        return $this->hasMany(\App\Models\InternComplaint::class, 'intern_session_id');
    }
    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }




}
