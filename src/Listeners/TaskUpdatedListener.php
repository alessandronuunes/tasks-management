<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Listeners;

use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Notifications\TaskUpdatedNotification;
use Filament\Notifications\Notification;

class TaskUpdatedListener
{
    public function handle(TaskUpdatedEvent $event): void
    {
        $task = $event->task;

        // Notifica todos os usuários atribuídos à tarefa
        foreach ($task->users as $user) {
            // Pula o usuário que fez a alteração
            if ($user->id === auth()->id()) {
                continue;
            }

            // Envia notificação Filament
            Notification::make()
                ->title('Tarefa Atualizada')
                ->body("A tarefa '{$task->name}' foi atualizada")
                ->icon('heroicon-o-bell')
                ->iconColor('warning')
                ->sendToDatabase($user);

            // Envia notificação por e-mail
            $user->notify(new TaskUpdatedNotification($task));
        }
    }
}
