<?php
namespace LocalheroPortal\Core\Traits;

use Illuminate\Notifications\DatabaseNotification;
use LocalheroPortal\Callcenter\Notifications\AppointmentMade;
use LocalheroPortal\Callcenter\Notifications\AppointmentRejected;
use LocalheroPortal\Callcenter\Notifications\LeadAmount;

trait ParsesNotifications
{
    /**
     * @param  bool    $withRead
     * @return array
     */
    public function get_parsed_notifications(bool $withRead = false): array
    {
        $notifications = collect($this->notifications()->get() ?? []);

        foreach ($notifications as $notification) {
            $this->parse_notification($notification);
        }

        $readNotifications = $notifications->where('read_at', '!=', null);

        $unreadNotifications = $notifications->where('read_at', '==', null);

        return compact('readNotifications', 'unreadNotifications');
    }

    /**
     * @param  DatabaseNotification $notification
     * @return void
     */
    public function parse_notification(DatabaseNotification $notification): void
    {
        switch ($notification->type) {
            case LeadAmount::class;
                $notification->icon = 'fa-sort-amount-down';
                $notification->link = route('admin.experts.index', ['expert' => $notification->data['related']]);
                break;
            case AppointmentRejected::class;
                $notification->icon = 'fa-ban';
                break;
            case AppointmentMade::class;
                $notification->icon = 'fa-calendar';
                $notification->link = route('experts.show', ['expert' => $this->id, 'appointment' => $notification->data['related']]);
                break;
            default;
                $notification->icon = 'fa-bell';
        }
    }
}
