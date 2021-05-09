<?php

namespace LocalheroPortal\Core\Feature\ChangeLeadState;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use LocalheroPortal\Callcenter\Notifications\AppointmentChanged;
use LocalheroPortal\Callcenter\Notifications\AppointmentMade;
use LocalheroPortal\Callcenter\Notifications\AppointmentStorno;
use LocalheroPortal\Callcenter\UseCase\LeadCallNotReachedUseCase;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\CalendarEventType;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadExpertAcceptance;
use LocalheroPortal\Models\LeadState;

class LeadStateChangeUseCase
{

    public Lead $lead;
    public LeadStateChangeRequest $request;

    public function __construct(Lead $lead, LeadStateChangeRequest $request)
    {
        $this->lead = $lead;
        $this->request = $request;
    }

    public function changeLeadState()
    {
        $this->cancelFutureAppointment();
        switch ($this->request->state) {
            case LeadState::NOT_REACHED:
                $this->changeToNotReached();
                break;
            case LeadState::RECALL:
                $this->changeToRecall();
                break;
            case LeadState::NO_INTEREST:
                $this->changeToNoInterest();
                break;
            case LeadState::APPOINTMENT:
                $this->changeToAppointment();
                break;
            case LeadState::BLACKLIST:
                $this->changeToBlacklist();
                break;
            case LeadState::APPOINTMENT_NEEDED:
                $this->changeToAppointmentNeeded();
                break;
            case LeadState::COMPETITION_PROTECTION:
                $this->changeToCompetitionProtection();
                break;
            default:
                break;
        }
        $this->lead->save();
    }

    public function cancelFutureAppointment()
    {
        if ($this->lead->status == LeadState::APPOINTMENT) {
            $lastAppointment = $this->lead->calendarEvents->last();
            if ($lastAppointment && now('Europe/Berlin')->isBefore($lastAppointment->event_begin)) {
                $lastAppointment->delete();
                $this->createStateChangeComment(CommentReason::APPOINTMENT_DELETED);
                if (App::environment('production')) {
                    Notification::send($this->lead->expert,
                        new AppointmentStorno($this->lead, $this->lead->closed_until));
                }
            }
        }
    }

    public function changeToNotReached()
    {
        $this->lead->status = LeadState::NOT_REACHED;
        $notReachedUseCase = new LeadCallNotReachedUseCase();
        $notReachedUseCase->onNotReached($this->lead);
        $this->createStateChangeComment(CommentReason::NOT_REACHED);
    }

    public function changeToRecall()
    {
        //TODO: previously not always set?
        $this->lead->agent_id = Auth::id();
        $this->lead->status = LeadState::RECALL;
        $this->lead->closed_until = Carbon::createFromTimestamp(strtotime($this->request->startDate));
        $this->createStateChangeComment(CommentReason::RECALL);
    }

    public function changeToNoInterest()
    {
        $this->lead->status = LeadState::NO_INTEREST;
        $this->lead->closed_until = now('Europe/Berlin')->setTime(8, 0)->addMonths(3);
        $this->createStateChangeComment(CommentReason::NO_INTEREST);
    }

    public function changeToAppointment()
    {
        $this->lead->status = LeadState::APPOINTMENT;
        $this->lead->expert_status = LeadExpertAcceptance::OPEN;
        $this->lead->closed_until = Carbon::createFromTimestamp(strtotime($this->request->startDate));
        $expert = $this->lead->expert;
        if (Auth::id() != $expert->id) {
            //TODO
            if ($this->lead->isDirty('status')) {
                $expert->notify(new AppointmentMade(Auth::user(), $this->lead));
            } else {
                $expert->notify(new AppointmentChanged($this->lead, $this->lead->getOriginal('closed_until'),
                    $this->lead->closed_until->toString()));
            }
        }

        $commentText = $this->getAppointmentCommentText();
        $this->createStateChangeComment(CommentReason::APPOINTMENT, $commentText);

        $appointment = new CalendarEvent();
        $appointment->type = CalendarEventType::CALLCENTER_APPOINTMENT;
        $appointment->body = $this->request->comment;
        $appointment->event_begin = $this->request->startDate;
        $appointment->event_end = $this->request->endDate;
        $appointment->options = [];
        $this->lead->calendarEvents()->save($appointment);
    }

    private function getAppointmentCommentText()
    {
        $dateTimeFormat = 'd.m.y H:i';
        $timeFormat = 'H:i';
        $startDate = Carbon::parse($this->request->startDate);
        $startFormatted = $startDate->format($dateTimeFormat);
        $endDate = Carbon::parse($this->request->endDate);
        $endFormatted = $endDate->format($dateTimeFormat);
        if ($startDate->isSameDay($endDate)) {
            $endFormatted = $endDate->format($timeFormat);
        }
        return "Start: $startFormatted, Ende: $endFormatted\n\n".$this->request->comment;
    }

    public function changeToBlacklist()
    {
        $this->lead->status = LeadState::BLACKLIST;
        $this->createStateChangeComment(CommentReason::BLACKLIST);
    }

    public function changeToAppointmentNeeded()
    {
        $this->lead->status = LeadState::APPOINTMENT_NEEDED;
        $this->createStateChangeComment(CommentReason::APPOINTMENT_NEEDED);
    }

    public function changeToCompetitionProtection()
    {
        $this->lead->status = LeadState::COMPETITION_PROTECTION;
        $this->lead->closed_until = Carbon::now('Europe/Berlin')->addMonths(6);
        $this->createStateChangeComment(CommentReason::COMPETITION_PROTECTION);
    }

    public function createStateChangeComment(string $reason, string $commentText = null)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->reason = $reason;
        $comment->body = $commentText ?? $this->request->comment;
        $comment->date = now('Europe/Berlin');
        $this->lead->comments()->save($comment);
    }
}