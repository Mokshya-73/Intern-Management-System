<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAdmin extends Model
{
    protected $table = 'system_admin';
    protected $fillable = ['reg_no', 'name'];

    public function user()
    {
        return $this->hasOne(UserCoreData::class, 'reg_no', 'reg_no');
    }
}
