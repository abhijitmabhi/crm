<?php

namespace LocalheroPortal\Callcenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\User;

class AppointmentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The Expert rejecting an appointment
     * @var User
     */
    protected $expert;

    /**
     * The Lead, who's appointment got rejected
     * @var Lead
     */
    protected $lead;

    /**
     * The reasoning, the $expert gave when declining the appointment
     * @var string
     */
    protected $reasoning;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $expert, Lead $lead, string $message)
    {
        $this->expert = $expert;
        $this->lead = $lead;
        $this->reasoning = $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed   $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'message'       => "Der Experte {$this->expert->name} hat einen für ihn vereinbarten Termin abgelehnt.",
            'short_message' => "Storno: {$this->expert->name}",
            'related'       => "{$this->expert->id}",
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
            ->subject("Termin wurde von {$this->expert->name} abgelehnt")
            ->from('noreply@localhero.de', 'Localhero Portal')
            ->greeting("Guten Tag $notifiable->name,")
            ->line("Der Experte {$this->expert->name} hat einen für ihn vereinbarten Termin abgelehnt.")
            ->line("Firmenname: {$this->lead->company_name}\nKontakt: {$this->lead->contact_name}")
            ->line("Telefon: " . $this->lead->phone1)
            ->line("Datum: " . $this->lead->closed_until->format('d.m.Y H:i'))
            ->line('Begründung:')
            ->line($this->reasoning)
            ->action('Details', route('callcenter.show', [
                'lead'         => $this->lead,
                'leads'        => Lead::forUser($this->expert)->stateOpen()->get(),
                'expert'       => $this->expert,
                'appointments' => Lead::forUser($this->expert)->expertRejected()->get(),
                'timer'        => 'false']))
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
