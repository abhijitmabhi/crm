<?php

namespace LocalheroPortal\Callcenter\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LocalheroPortal\Models\Lead;
use stdClass;

class PushRecallNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var mixed
     */
    public $message;

    /**
     * @var int
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $leadId)
    {
        $lead = Lead::find($leadId);
        $this->userId = $lead->agent_id;
        $this->constructMessage($lead);
    }

    public function broadcastAs()
    {
        return "modalNotification";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.$this->userId.notifications");
    }

    /**
     * @param Lead $lead
     */
    private function constructMessage(Lead $lead)
    {
        $message = new stdClass();
        $timeToRecall = $lead->closed_until->diffInMinutes(now('Europe/Berlin'));
        $message->title = "Wiedervorlage";
        $message->link = route('callcenter.show', ['lead' => $lead->id]);
        $message->okTitle = "Zum Lead";
        $message->cancelTitle = "Abbrechen";
        $message->body = "In $timeToRecall {$this->getTimeUnit($timeToRecall)} wird die Wiedervorlage für $lead->company_name fällig.";
        $this->message = $message;
    }

    /**
     * @param int $time
     */
    private function getTimeUnit($time)
    {
        if ($time == 1) {
            return "Minute";
        }
        return "Minuten";
    }
}
