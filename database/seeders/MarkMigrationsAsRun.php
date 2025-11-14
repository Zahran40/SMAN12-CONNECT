<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkMigrationsAsRun extends Seeder
{
    public function run(): void
    {
        // Mark default Laravel migrations as already run
        // so they don't conflict with existing tables
        DB::table('migrations')->insert([
            [
                'migration' => '0001_01_01_000000_create_users_table',
                'batch' => 1
            ],
            [
                'migration' => '0001_01_01_000001_create_cache_table',
                'batch' => 1
            ],
            [
                'migration' => '0001_01_01_000002_create_jobs_table',
                'batch' => 1
            ],
        ]);
    }
}
