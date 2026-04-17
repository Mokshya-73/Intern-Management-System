<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionTask extends Model
{
    protected $fillable = [
        'intern_session_id', 'task_name', 'rating', 'description'
    ];

    public function session()
    {
        return $this->belongsTo(InternSession::class, 'intern_session_id');
    }
}
