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
        // Se não houver lista de usuários autorizados, permite acesso a todos
        if (empty($this->authorizedUsers)) {
            return;
        }
        
        // Compartilhar a lista de usuários autorizados com o aplicativo
        app()->instance('tasks-management.authorized-users', $this->authorizedUsers);
    }

    // Modificar o método para esconder recursos em vez de tentar desregistrá-los
    protected function hideResources(): void
    {
        // Esconder os recursos do plugin para usuários não autorizados
        // usando políticas ou modificando a visibilidade
        
        // Para cada recurso do plugin, definir uma política que impede o acesso
        foreach ([TaskResource::class, TaskTagResource::class, TaskCustomFieldResource::class] as $resource) {
            // Sobrescrever o método can para negar acesso
            $resource::canViewAny(function () {
                return false;
            });
        }
        
        // Para widgets, podemos usar uma abordagem semelhante
        foreach ([TasksOverviewWidget::class, LatestTasksWidget::class] as $widget) {
            $widget::canView(function () {
                return false;
            });
        }
    }

    public function authorizedUsers(array $users): static
    {
        
        $this->authorizedUsers = $users;
        
        return $this;
    }

    protected function isAuthorized(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Verificar se o ID ou email do usuário está na lista de usuários autorizados
        foreach ($this->authorizedUsers as $authorizedUser) {
            if (
                // Verificar se é um ID de usuário
                (is_numeric($authorizedUser) && $user->id == $authorizedUser) ||
                // Verificar se é um email
                (is_string($authorizedUser) && $user->email == $authorizedUser)
            ) {
                return true;
            }
        }
        
        return false;
    }

    // Adicionar um novo método para desregistrar recursos se necessário
    protected function unregisterResources(Panel $panel): void
    {
        // Remover recursos do plugin
        $panel->getResources = function () use ($panel) {
            return collect($panel->getResources())->filter(function ($resource) {
                return !str_contains($resource, 'Alessandronuunes\\TasksManagement');
            })->toArray();
        };
        
        // Remover widgets do plugin
        $panel->getWidgets = function () use ($panel) {
            return collect($panel->getWidgets())->filter(function ($widget) {
                return !str_contains($widget, 'Alessandronuunes\\TasksManagement');
            })->toArray();
        };
    }
}
