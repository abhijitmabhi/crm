<?php

namespace LocalheroPortal\Core\Feature\CustomerProgress;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\User\User;

class AccountReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;
    protected string $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Account freigeschaltet!')
            ->from('noreply@localhero.de', 'LocalHero Portal')
            ->markdown('notifications.CustomerAccountReadyNotificationView', ['user' => $this->user, 'password' => $this->password]);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
