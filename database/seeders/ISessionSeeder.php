<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ISessionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('i_sessions')->insert([
            [
                'session_name' => 'Session 1',
                'session_time_period' => '1-3 Months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'session_name' => 'Session 2',
                'session_time_period' => '4-6 Months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'session_name' => 'Session 3',
                'session_time_period' => '7-9 Months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'session_name' => 'Session 4',
                'session_time_period' => '10-12 Months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
