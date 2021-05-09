<?php

namespace LocalheroPortal\Callcenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\Lead;

class AppointmentStorno extends Notification
{
    use Queueable;

    /**
     * @var string $date
     */
    protected $date;

    /**
     * @var Lead $lead
     */
    protected $lead;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, string $date)
    {
        $this->lead = $lead;
        $this->date = $date;
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
            'message'       => "{$notifiable->name}'s Termin bei {$this->lead->name} wurde storniert.",
            'short_message' => "Termin bei {$this->lead->name} stornier.",
            'related'       => (string) $notifiable->id,
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
            ->subject('Termin Storno')
            ->from('noreply@localhero.de', 'Local Hero Portal')
            ->greeting('Guten Tag ' . $notifiable->name . ',')
            ->line("Der Termin bei {$this->lead->company_name} am {$this->date} wurde storniert.")
            ->action('Kalender ansehen', route('experts.show', ['expert' => $notifiable->id]))
            ->salutation('Dein Local Hero Team');
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
