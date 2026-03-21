<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Employee::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ No users found. Skipping EmployeeSeeder.');
            return;
        }

        $employees = [
            [
                'user_id' => $users->where('email', 'admin@example.com')->first()?->id ?? 1,
                'employee_code' => 'EMP001',
                'department' => 'Management',
                'designation' => 'Administrator',
                'phone' => '9876543001',
                'address' => 'Admin Office',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'joining_date' => '2024-01-01',
            ],
            [
                'user_id' => $users->where('email', 'hr@example.com')->first()?->id ?? 2,
                'employee_code' => 'EMP002',
                'department' => 'Human Resources',
                'designation' => 'HR Manager',
                'phone' => '9876543002',
                'address' => 'HR Department',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'joining_date' => '2024-01-15',
            ],
            [
                'user_id' => $users->where('email', 'staff@example.com')->first()?->id ?? 3,
                'employee_code' => 'EMP003',
                'department' => 'Sales',
                'designation' => 'Sales Executive',
                'phone' => '9876543003',
                'address' => 'Sales Office',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'joining_date' => '2024-02-01',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        $this->command->info('✅ Employees seeded successfully!');
    }
}
