<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniLoc extends Model
{
    protected $table = 'uni_locs';
    protected $fillable = ['uni_id', 'location'];

    public function unis()
    {
        return $this->belongsTo(Unis::class, 'uni_id');
    }

}
