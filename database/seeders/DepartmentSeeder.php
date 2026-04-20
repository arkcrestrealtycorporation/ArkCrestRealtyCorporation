<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\ExpenseCategory;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Administrative', 'slug' => 'admin', 'categories' => [
                'Pantry Supplies','Office Rental','Utilities','Office Supplies and Equipments',
                'Maintenance and Repairs','Transportation','Food/ Meals','Medical Supplies',
                'Cleaning / Janitorial Supplies','Miscellaneous'
            ]],
            ['name' => 'Sales & Marketing', 'slug' => 'sales_and_marketing', 'categories' => [
                'Advertisement Cost','Sales Incentives','Agent Allowances',
                'Transportation','Food/ Meals','Sales Miscellaneous'
            ]],
            ['name' => 'Human Resource', 'slug' => 'hr', 'categories' => [
                'Office Staff Allowances','Recruitment and Hiring','Licenses and Permits',
                'Transportation','Events/ Program','Miscellaneous'
            ]],
            ['name' => 'Finance', 'slug' => 'finance', 'categories' => [
                'Retention Fees','Penalty/ Fines','Tax and Licenses','Miscellaneous'
            ]],
            ['name' => 'Executive', 'slug' => 'executive', 'categories' => [
                'Food/ Meals','Transportation','Repairs and Maintenance','Miscellaneous'
            ]],
        ];

        foreach ($data as $d) {
            $dept = Department::firstOrCreate(
                ['slug' => $d['slug']],
                ['name' => $d['name'], 'allowable_budget' => 0]
            );
            // Update name in case it was renamed
            $dept->update(['name' => $d['name']]);

            foreach ($d['categories'] as $cat) {
                ExpenseCategory::firstOrCreate(
                    ['department_id' => $dept->id, 'name' => $cat]
                );
            }
        }
    }
}
