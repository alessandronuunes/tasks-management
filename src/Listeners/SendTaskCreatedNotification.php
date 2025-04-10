<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Listeners;

use Alessandronuunes\TasksManagement\Events\TaskCreatedEvent;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SendTaskCreatedNotification
{
    public function handle(TaskCreatedEvent $event): void
    {
        foreach ($event->task->users as $user) {
            Notification::make()
                ->title(__('tasks-management::notifications.task_created.title'))
                ->body(__('tasks-management::notifications.task_created.body', [
                    'task' => $event->task->name,
                ]))
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(TaskResource::getUrl('edit', ['record' => $event->task])),
                ])
                ->sendToDatabase($user);
        }
    }
}
