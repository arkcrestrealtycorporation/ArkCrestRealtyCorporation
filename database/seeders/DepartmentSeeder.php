<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Admin', 'slug' => 'admin', 'allowable_budget' => 600000],
            ['name' => 'Sales & Marketing', 'slug' => 'sales', 'allowable_budget' => 1000000],
            ['name' => 'HR', 'slug' => 'hr', 'allowable_budget' => 500000],
            ['name' => 'Finance', 'slug' => 'finance', 'allowable_budget' => 800000],
            ['name' => 'Executive', 'slug' => 'executive', 'allowable_budget' => 700000],
            ['name' => 'CAPEX', 'slug' => 'capex', 'allowable_budget' => 400000],
        ];

        foreach ($departments as $dept) {
            \App\Models\Department::create($dept);
        }
    }
}
