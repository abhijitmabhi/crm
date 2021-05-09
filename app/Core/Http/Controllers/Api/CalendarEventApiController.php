<?php


namespace LocalheroPortal\Core\Http\Controllers\Api;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\CalendarEventType;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use function Sentry\captureException;

class CalendarEventApiController extends Controller
{

    public function getCalendarEvent(CalendarEvent $calendarEvent)
    {
        return $calendarEvent;
    }

    public function createCalendarEvent(Request $request)
    {
        $appointment = new CalendarEvent($request->get('event'));
        $appointment->type = CalendarEventType::CALLCENTER_APPOINTMENT;
        $appointment->options = [];
        $lead = Lead::withTrashed()->whereId($request->get('leadId'))->first();
        $lead->calendarEvents()->save($appointment);

        return Response::json(['message' => 'event created']);
    }

    public function deleteCalendarEvent(CalendarEvent $calendarEvent): JsonResponse
    {
        DB::beginTransaction();
        try {
            $calendarEvent->delete();
            $lead = $calendarEvent->leads()->first();
            $commentText = "Termin wurde gelöscht:\n\n".$calendarEvent->body;
            $comment = $this->createLeadComment(CommentReason::APPOINTMENT_DELETED, $commentText);
            $lead->comments()->save($comment);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            captureException($exception);
            return Response::json(['errors' => ['Termin löschen fehlgeschlagen.']]);
        }
        return Response::json(['message' => 'event deleted']);
    }

    public function createLeadComment(string $reason, string $commentText): Comment
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->reason = $reason;
        $comment->body = $commentText;
        $comment->date = now('Europe/Berlin');
        return $comment;
    }
}