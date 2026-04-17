<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unis extends Model
{
    protected $table = 'unis';
    protected $fillable = ['uni_name'];

    public function loc()
    {
        return $this->hasMany(UniLoc::class, 'uni_id');
    }

}
