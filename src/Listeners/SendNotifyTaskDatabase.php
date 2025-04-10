<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Listeners;

use Alessandronuunes\TasksManagement\Events\TaskNotifyEvent;
use Alessandronuunes\TasksManagement\Models\Task;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use stdClass;

class SendNotifyTaskDatabase
{
    public function handle(TaskNotifyEvent $event): void
    {
        foreach ($event->task->users as $recipient) {
            $message = $this->formatMessage($event->task);
            Notification::make()
                ->title($message->title)
                ->body($message->body)
                ->actions([
                    Action::make('View')
                        ->label('Ver mensagem')
                        ->button()
                        ->markAsRead()
                        ->url("/tasks/{$event->task->id}/edit")
                ])
                ->sendToDatabase($recipient);
        }
    }

    private function formatMessage(Task $task): stdClass
    {
        $user = $task->user;

        return (object) [
            'title' => __('tasks-management::notifications.task.created.title'),
            'body' => __('tasks-management::notifications.task.created.body', [
                'user' => $user->full_name
            ]),
        ];
    }
}