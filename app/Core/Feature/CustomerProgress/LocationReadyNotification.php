<?php

namespace LocalheroPortal\Core\Feature\CustomerProgress;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\LLI\Location;

class LocationReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Neuer Standort freigeschaltet!')
            ->from('noreply@localhero.de', 'LocalHero Portal')
            ->markdown('notifications.LocationReadyNotificationView', ['location' => $this->location]);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
