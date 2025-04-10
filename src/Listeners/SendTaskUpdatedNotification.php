<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Listeners;

use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SendTaskUpdatedNotification
{
    public function handle(TaskUpdatedEvent $event): void
    {
        foreach ($event->task->users as $user) {
            Notification::make()
                ->title(__('tasks-management::notifications.task_updated.title'))
                ->body(__('tasks-management::notifications.task_updated.body', [
                    'task' => $event->task->name,
                ]))
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(TaskResource::getUrl('edit', ['record' => $event->task]))
                ])
                ->sendToDatabase($user);
        }
    }
}