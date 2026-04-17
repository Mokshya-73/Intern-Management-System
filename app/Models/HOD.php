<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hod extends Model
{
    protected $table = 'hod';
    protected $fillable = ['reg_no', 'name', 'department', 'description'];
    public $timestamps = true;

    public function userCoreData()
    {
        return $this->belongsTo(UserCoreData::class, 'reg_no', 'reg_no');
    }
    public function department()
    {
        return $this->hasOne(DepartmentHod::class);
    }

    public function supervisors()
    {
        return $this->hasManyThrough(Supervisor::class, HODSupervisor::class, 'hod_id', 'id', 'id', 'supervisor_id');
    }



}
