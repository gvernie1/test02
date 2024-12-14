<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InspectDatabase extends Command
{
    protected $signature = 'db:inspect {table?}';
    protected $description = 'Inspect database schema using raw queries';

    public function handle()
    {
        $table = $this->argument('table') ?? 'posts';
        
        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist.");
            return 1;
        }

        $this->info("\nTable: {$table}");
        $this->info("------------------------");

        // For MySQL
        if (config('database.default') === 'mysql') {
            $columns = DB::select("SHOW FULL COLUMNS FROM {$table}");
            foreach ($columns as $column) {
                $this->info(sprintf(
                    "%-20s %-20s %-8s %s",
                    $column->Field,
                    $column->Type,
                    $column->Null === 'YES' ? 'NULL' : 'NOT NULL',
                    $column->Extra
                ));
            }
        }
    }
}