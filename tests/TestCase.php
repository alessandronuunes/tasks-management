<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Tests;

use Alessandronuunes\TasksManagement\TasksManagementServiceProvider;
use Filament\FilamentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TasksManagementServiceProvider::class,
            FilamentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
