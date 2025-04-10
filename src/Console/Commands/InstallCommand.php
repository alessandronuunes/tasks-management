<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'tasks-management:install';

    protected $description = 'Install the tasks management package';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'tasks-management-config',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'tasks-management-migrations',
            '--force' => true,
        ]);

        $this->info('Tasks Management was installed successfully.');

        return self::SUCCESS;
    }
}