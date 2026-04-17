<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\University;
use App\Models\UniversityLocation;
use App\Models\Department;
use App\Models\Specialization;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            [
                'name' => 'APIIT',
                'short_name' => 'APIIT',
                'type' => 'Private',
                'locations' => ['Colombo', 'Kandy'],
                'departments' => [
                    'School of Computing' => ['Software Engineering', 'Data Science'],
                    'School of Business' => ['Business Management', 'Marketing']
                ]
            ],
            [
                'name' => 'ICBT Campus',
                'short_name' => 'ICBT',
                'type' => 'Private',
                'locations' => ['Colombo', 'Kandy', 'Kurunegala'],
                'departments' => [
                    'IT Department' => ['Computer Networks', 'Cybersecurity'],
                    'Business Department' => ['Finance', 'HR Management']
                ]
            ],
            [
                'name' => 'ACBT',
                'short_name' => 'ACBT',
                'type' => 'Private',
                'locations' => ['Colombo'],
                'departments' => [
                    'Computing' => ['Software Engineering'],
                    'Management' => ['International Business']
                ]
            ],
            [
                'name' => 'University of Moratuwa',
                'short_name' => 'UOM',
                'type' => 'Public',
                'locations' => ['Moratuwa'],
                'departments' => [
                    'Engineering' => ['Electrical', 'Civil', 'Mechanical'],
                    'IT' => ['Computer Science']
                ]
            ],
            [
                'name' => 'University of Sri Jayewardenepura',
                'short_name' => 'USJP',
                'type' => 'Public',
                'locations' => ['Nugegoda'],
                'departments' => [
                    'Management Studies' => ['Accountancy', 'Business Economics'],
                    'Applied Sciences' => ['Biological Science', 'Physical Science']
                ]
            ],
        ];

        foreach ($universities as $uniData) {
            $uni = University::create([
                'name' => $uniData['name'],
                'short_name' => $uniData['short_name'],
                'type' => $uniData['type'],
                'established_year' => now()->year,
                'email' => strtolower($uniData['short_name']) . '@example.com',
                'phone' => '0110000000',
                'website_url' => 'https://www.' . strtolower($uniData['short_name']) . '.lk',
            ]);

            foreach ($uniData['locations'] as $city) {
                UniversityLocation::create([
                    'university_id' => $uni->id,
                    'city' => $city,
                    'address' => $city . ' Branch',
                    'postcode' => '00000',
                ]);
            }

            foreach ($uniData['departments'] as $deptName => $specializations) {
                $department = Department::create([
                    'university_id' => $uni->id,
                    'name' => $deptName,
                ]);

                foreach ($specializations as $spec) {
                    Specialization::create([
                        'department_id' => $department->id,
                        'name' => $spec,
                    ]);
                }
            }
        }
    }
}
