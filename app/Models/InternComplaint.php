<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternComplaint extends Model
{
    protected $fillable = [
        'intern_reg_no',
        'intern_session_id',
        'message',
        'status',
    ];

    public function intern()
    {
        return $this->belongsTo(InternProfile::class, 'intern_reg_no', 'reg_no');
    }

    public function internSession()
    {
        return $this->belongsTo(InternSession::class, 'intern_session_id');
    }
}
