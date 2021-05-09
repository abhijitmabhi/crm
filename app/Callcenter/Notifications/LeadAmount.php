<?php

namespace LocalheroPortal\Callcenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LocalheroPortal\Models\User\User;

class LeadAmount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \LocalheroPortal\Models\User\User $expert
     */
    protected $expert;

    /**
     * @var int
     */
    protected $leadAmount;

    /**
     * @param int                        $leadAmount [description]
     * @param \LocalheroPortal\Models\User\User $expert     [description]
     */
    public function __construct(int $leadAmount, User $expert)
    {
        $this->leadAmount = $leadAmount;
        $this->expert = $expert;
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
            'message'       => "Es sind noch {$this->leadAmount} Leads in der Pipeline für  {$this->expert->name}",
            'short_message' => "Wenige Leads: {$this->expert->name}",
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
        $message = new MailMessage();
        $message->greeting('Hi ' . $notifiable->name . ',');
        $message->line("Es sind noch $this->leadAmount Leads in der Pipeline für " . $this->expert->name);
        $message->action('Leads Importieren', route('experts.index', ['expert' => $this->expert->id]));
        $message->salutation('Es gibt Handlungsbedarf!');
        if ($this->leadAmount <= 50) {
            $message->error();
        }
        return $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed   $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
}
