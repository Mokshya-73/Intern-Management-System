<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $fillable = [
        'intern_reg_no',
        'complaint',
        'supervisor_reg_no',
        'reason_for_removal',
        'status',
    ];

    public function intern()
    {
        return $this->belongsTo(InternProfile::class, 'intern_reg_no', 'reg_no');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_reg_no', 'reg_no');
    }

    public function internSession()
    {
        return $this->belongsTo(InternSession::class, 'intern_reg_no', 'reg_no');
    }
}
