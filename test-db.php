<?php

// Simple database connection test
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Database Connection...\n\n";

try {
    // Test connection
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connected successfully!\n\n";
    
    // Check if tables exist
    $tables = ['departments', 'expense_categories', 'expenses'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "✓ Table '$table' exists (rows: $count)\n";
        } catch (Exception $e) {
            echo "✗ Table '$table' NOT found\n";
        }
    }
    
    echo "\n";
    
    // Check departments
    $depts = DB::table('departments')->get();
    echo "Departments in database: " . $depts->count() . "\n";
    foreach ($depts as $dept) {
        echo "  - {$dept->name} (Budget: ₱" . number_format($dept->allowable_budget, 2) . ")\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
