<?php

namespace LocalheroPortal\Callcenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\User;

class AppointmentMade extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var mixed
     */
    protected $callagent;

    /**
     * @var mixed
     */
    protected $lead;

    /**
     * @param \LocalheroPortal\Models\User\User              $callagent [description]
     * @param \LocalheroPortal\Models\Lead $lead      [description]
     */

    public function __construct(User $callagent, Lead $lead)
    {
        $this->callagent = $callagent;
        $this->lead = $lead;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed   $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message'       => "Ein Neuer Termin bei {$this->lead->company_name} wurde für dich vereinbart",
            'short_message' => "Termin: {$this->lead->company_name}",
            'related'       => "{$this->lead->id}",
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed                                            $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Neuer Termin')
            ->from('noreply@localhero.de', 'Localhero Portal')
            ->markdown('experts.email.appointment.made', ['expert' => $notifiable, 'lead' => $this->lead]);
    }

    /**
     * @param mixed $notifiable
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
}
