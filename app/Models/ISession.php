<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISession extends Model
{
    use HasFactory;

    protected $table = 'i_sessions';

    protected $fillable = [
        'session_name',
        'session_time_period',
        'location',
    ];

    // Relationship: One session has many intern sessions
    public function internSessions()
    {
        return $this->hasMany(InternSession::class, 'session_id');
    }
}
