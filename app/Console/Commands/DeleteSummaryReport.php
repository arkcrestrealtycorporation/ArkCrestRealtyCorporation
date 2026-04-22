<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SummaryReport;

class DeleteSummaryReport extends Command
{
    protected $signature = 'summary:delete {month} {year}';
    protected $description = 'Delete a summary report record by month and year';

    public function handle()
    {
        $month = $this->argument('month');
        $year = $this->argument('year');

        $deleted = SummaryReport::where('month', $month)->where('year', $year)->delete();

        if ($deleted) {
            $this->info("Deleted summary report for month {$month}, year {$year}.");
        } else {
            $this->warn("No record found for month {$month}, year {$year}.");
        }
    }
}
