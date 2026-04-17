<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HODSupervisor extends Model
{
    protected $table = 'hod_supervisors'; 
    protected $fillable = ['hod_id', 'supervisor_id', 'is_active', 'removal_reason'];

    public function hod()
    {
        return $this->belongsTo(Hod::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
