<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CourseEnrolledNotification extends Notification
{
    use Queueable;

    public function __construct(public Course $course) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Вы записались на курс')
            ->greeting('Здравствуйте, ' . $notifiable->name)
            ->line('Вы успешно записались на курс: ' . $this->course->title)
            ->action('Перейти к курсу', url('/student/courses/' . $this->course->id))
            ->line('Успехов в обучении!');
    }
}