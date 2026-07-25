<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Welcome to '. Setting::get('website_name', 'School') .' Portal!')
            ->greeting('Hello, ' . $notifiable->name. '!');

        $type = $notifiable->type;

        if ($type === User::TEACHER) {
            $mail->line('Welcome to our School Portal! You have been registered as a Teacher.')
                ->line('Here are your account details:')
                ->line('Unique ID: ' . $notifiable->unique_id)
                ->line('Email: ' . $notifiable->email)
                ->line('Temporary Password: password')
                ->line('Please update your password after logging in.');
        } elseif ($type === User::STUDENT) {
            $mail->line('Welcome to our School Portal! You have been registered as a Student.')
                ->line('Here are your account details:')
                ->line('Unique ID: ' . $notifiable->unique_id)
                ->line('Email: ' . $notifiable->email)
                ->line('Temporary Password: password')
                ->line('Please update your password after logging in.');
        } elseif ($type === User::PARENT) {
            $mail->line('Welcome to our School Portal! You have been registered as a Parent.')
                ->line('Here are your account details:')
                ->line('Email: ' . $notifiable->email)
                ->line('Temporary Password: password')
                ->line('Please update your password after logging in.');
        } else {
            $mail->line('Welcome to our School Portal! You have been registered as an Administrator.')
                ->line('Here are your account details:')
                ->line('Email: ' . $notifiable->email)
                ->line('Temporary Password: password')
                ->line('Please update your password after logging in.');
        }

        return $mail
            ->action('Login to Portal', route('login'))
            ->line('Thank you for joining us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
