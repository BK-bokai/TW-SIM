<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterUser extends Notification
{
    use Queueable;
    protected $activasion;
    protected $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($activasion,$url)
    {
        $this->activasion=$activasion;
        $this->url       = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('confirm',['active'=>$notifiable]);
        return (new MailMessage)
            ->subject('已註冊成功請進行下一步認證')
            ->markdown('email.RegisterUser')
            ->line('您於BK網站申請註冊，請點選下列網址進行進一步認證')
            ->action('進行下一步認證', $this->url)
            ->line('若您並未申請此動作，請忽略此信');
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
            //
        ];
    }
}
