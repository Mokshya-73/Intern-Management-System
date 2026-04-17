<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approver1 extends Model
{
    use HasFactory;

    protected $table = 'approver_1s';

    protected $fillable = [
        'reg_no',
        'name',
        'designation',
        'description',
        'university', 
    ];


    public function userCoreData()
    {
        return $this->belongsTo(UserCoreData::class, 'reg_no', 'reg_no');
    }
}
