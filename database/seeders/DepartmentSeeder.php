<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Admin',            'slug' => 'admin'],
            ['name' => 'Sales & Marketing','slug' => 'sales_and_marketing'],
            ['name' => 'HR',               'slug' => 'hr'],
            ['name' => 'Finance',          'slug' => 'finance'],
            ['name' => 'Executive',        'slug' => 'executive'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['slug' => $dept['slug']],
                ['name' => $dept['name'], 'allowable_budget' => 0]
            );
        }
    }
}
