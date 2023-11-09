<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Invitation extends Notification
{
    use Queueable;

    protected $company_name;
    protected $job_title;
    protected $refer_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($company_name, $job_title, $refer_link = null)
    {
        $this->company_name = $company_name;
        $this->job_title = $job_title;
        $this->refer_link = $refer_link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data' => $this->company_name . ' đã mời bạn ứng tuyển cho công việc: ' . $this->job_title . '.'
                . ($this->refer_link ? ' Bạn có thể xem chi tiết công việc tại: ' . $this->refer_link : ''),
        ];
    }
}
