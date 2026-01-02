<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LogAktivitas;

class CleanupOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:cleanup {--days=90 : Number of days to keep logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old log activities to maintain database performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Starting cleanup of logs older than {$days} days...");
        
        $cutoffDate = now()->subDays($days);
        
        // Count logs to be deleted
        $count = LogAktivitas::where('waktu', '<', $cutoffDate)->count();
        
        if ($count === 0) {
            $this->info('No old logs found to cleanup.');
            return 0;
        }
        
        if ($this->confirm("Found {$count} logs to delete. Continue?", true)) {
            // Delete old logs
            $deleted = LogAktivitas::where('waktu', '<', $cutoffDate)->delete();
            
            $this->info("Successfully deleted {$deleted} old log entries.");
            
            // Log this cleanup action
            log_activity(
                'Cleanup',
                "Automated cleanup deleted {$deleted} log entries older than {$days} days",
                'log_aktivitas',
                null,
                'DELETE'
            );
            
            return 0;
        }
        
        $this->warn('Cleanup cancelled.');
        return 1;
    }
}
