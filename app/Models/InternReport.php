<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternReport extends Model
{
    use HasFactory;

    protected $fillable = ['intern_reg_no', 'supervisor_id', 'issue_description', 'resolved', 'hod_response'];

    // Define the relationship with the intern profile (intern_reg_no)
    public function intern()
    {
        return $this->belongsTo(InternProfile::class, 'intern_reg_no', 'reg_no');
    }

    // Define the relationship with the supervisor
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }
}
