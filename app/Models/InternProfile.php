<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class InternProfile extends Authenticatable
{
    protected $table = 'intern_profile';
    protected $primaryKey = 'reg_no';
    public $incrementing = false;

   protected $fillable = [
    'reg_no','is_active','status','name','certificate_name', 'mobile', 'email', 'city', 'nic',
    'training_start_date', 'training_end_date', 'description',
    'google_id', 'google_email', 'password', 'role_id','uni_id',    
    'uni_loc_id'   
    ];

    public function core()
    {
        return $this->belongsTo(UserCoreData::class, 'reg_no', 'reg_no');
    }

    // Relationship with Complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'intern_reg_no', 'reg_no');
    }

    public function internSessions()
    {
        return $this->hasMany(\App\Models\InternSession::class, 'reg_no', 'reg_no');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'id');
    }
    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
