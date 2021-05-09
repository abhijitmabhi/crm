<?php

namespace LocalheroPortal\Callcenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\Lead;

class AppointmentChanged extends Notification
{
    use Queueable;

    /**
     * @var Lead $lead
     */
    protected $lead;

    /**
     * @var string $newDate
     */
    protected $newDate;

    /**
     * @var string $oldDate
     */
    protected $oldDate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, string $oldDate, string $newDate)
    {
        $this->lead = $lead;
        $this->oldDate = $oldDate;
        $this->newDate = $newDate;
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
            'message'       => "Der Termin bei {$this->lead->company_name} wurde auf {$this->newDate} verlegt",
            'short_message' => "Termin verlegt: {$this->lead->company_name}",
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
            ->subject('Termin Verlegt')
            ->from('noreply@localhero.de', 'Local Hero Portal')
            ->greeting('Guten Tag ' . $notifiable->name . ',')
            ->line("Dein Termin bei {$this->lead->company_name} am {$this->oldDate} wurde verlegt.")
            ->line("Firmenname: " . $this->lead->company_name)
            ->line("Kontakt: " . $this->lead->contact_name)
            ->line("Telefon: " . $this->lead->phone1)
            ->line("Datum: " . $this->newDate)
            ->action('Details', route('experts.show', ['expert' => $notifiable->id, 'appointment' => $this->lead->id]))
            ->salutation('Dein LocalHero Team');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed   $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
}
