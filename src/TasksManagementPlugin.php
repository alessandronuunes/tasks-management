<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Filament\Panel;
use Filament\Contracts\Plugin;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Alessandronuunes\TasksManagement\Filament\Widgets\LatestTasksWidget;
use Alessandronuunes\TasksManagement\Filament\Widgets\TasksOverviewWidget;

class TasksManagementPlugin implements Plugin
{
    protected array $authorizedUsers = [];
    protected $userQueryModifier = null;
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'tasks-management';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                TaskResource::class,
                TaskTagResource::class,
                TaskCustomFieldResource::class,
            ])
            ->widgets([
                TasksOverviewWidget::class,
                LatestTasksWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        // Compartilhar o modificador de consulta de usuários
        if ($this->userQueryModifier) {
            app()->instance('tasks-management.user-query-modifier', $this->userQueryModifier);
        }
        
        // Se não houver lista de usuários autorizados, permite acesso a todos
        if (empty($this->authorizedUsers)) {
            return;
        }
        // Compartilhar a lista de usuários autorizados com o aplicativo
        app()->instance('tasks-management.authorized-users', $this->authorizedUsers);

        
    }

    public function authorizedUsers(array $users): static
    {
        
        $this->authorizedUsers = $users;
        
        return $this;
    }

    public function userQueryModifier(callable $modifier): static
    {
        $this->userQueryModifier = $modifier;
        
        return $this;
    }

    public function getUserQueryModifier(): ?callable
    {
        return $this->userQueryModifier;
    }
    
    /**
     * Configura o logging para o plugin
     */
    public function withLogging(string $driver, string $logModel, string $relationName = 'auditable'): static
    {
        if (!config('tasks-management.logging.enabled')) {
            return $this;
        }
        
        config([
            'tasks-management.logging.driver' => $driver,
            'tasks-management.models.activity_log' => $logModel,
            'tasks-management.logging.options.relation_name' => $relationName,
        ]);
        
        return $this;
    }
}
