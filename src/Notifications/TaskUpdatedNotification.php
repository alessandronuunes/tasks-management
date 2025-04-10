<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(private $task)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Tarefa Atualizada: '.$this->task->name)
            ->line('Uma tarefa que você está atribuído foi atualizada.')
            ->line('Tarefa: '.$this->task->name)
            ->action('Ver Tarefa', url('/admin/tasks/'.$this->task->id.'/edit'))
            ->line('Obrigado por usar nosso sistema!');
    }
}
