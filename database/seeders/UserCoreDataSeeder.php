<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserCoreData;

class UserCoreDataSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['reg_no' => 1001, 'role_id' => 1, 'email' => 'intern@example.com'],
            ['reg_no' => 1002, 'role_id' => 2, 'email' => 'supervisor@example.com'],
            ['reg_no' => 1003, 'role_id' => 3, 'email' => 'hod@example.com'],
            ['reg_no' => 1004, 'role_id' => 4, 'email' => 'approver1@example.com'],
            ['reg_no' => 1005, 'role_id' => 5, 'email' => 'approver2@example.com'],
            ['reg_no' => 1006, 'role_id' => 6, 'email' => 'admin@example.com'],
        ];

        foreach ($users as $user) {
            UserCoreData::updateOrCreate(
                ['reg_no' => $user['reg_no']],
                [
                    'role_id' => $user['role_id'],
                    'email' => $user['email'],
                    'password' => Hash::make('12345678')
                ]
            );
        }
    }
}
