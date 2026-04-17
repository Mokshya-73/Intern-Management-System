<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['university_id', 'location_id', 'name'];


    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }

    public function location()
{
    return $this->belongsTo(UniversityLocation::class);
}



    // public function hodAssignment()
    // {
    //     return $this->hasOne(DepartmentHod::class);
    // }
    public function departmentHod()
    {
        return $this->hasOne(DepartmentHod::class, 'department_id')->where('is_active', 1);
    }


}
