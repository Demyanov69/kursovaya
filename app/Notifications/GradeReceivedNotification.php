<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GradeReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $lessonTitle = $this->submission->lesson->title ?? 'урок';

        return (new MailMessage)
            ->subject('Вам выставлена оценка')
            ->greeting('Здравствуйте, ' . $notifiable->name)
            ->line("Вам выставлена оценка за урок: {$lessonTitle}")
            ->line("Статус: {$this->submission->status}")
            ->action('Посмотреть результат', url('/student/submissions/status/' . $this->submission->lesson_id));
    }
}