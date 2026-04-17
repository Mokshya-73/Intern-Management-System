<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccessLevel;

class UserAccessLevelSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            1 => 'Intern',
            2 => 'Supervisor',
            3 => 'Head of Department',
            4 => 'Approver 1',
            5 => 'Approver 2',
            6 => 'System Admin',
        ];

        foreach ($roles as $id => $role) {
            UserAccessLevel::updateOrCreate(['id' => $id], ['role' => $role]);
        }
    }
}
