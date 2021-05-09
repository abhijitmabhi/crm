<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Callcenter\Http\Resources\CommentSingleResource;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\CalendarEventType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\CalendarEvent;

class LeadCommentController extends Controller
{
    /**
     * @param Lead $lead
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return Response::json(['message' => 'Lead deleted']);
    }

    /**
     *
     * Returns the comments associated with the lead.
     *
     * Defaults:
     * * Sorting direction: descending
     *
     * @param Request  $request
     * @param  Lead    $lead     the Lead in question
     * @return mixed
     */
    public function index(Request $request, Lead $lead)
    {
        $comments = $lead->comments;
        $comments = $comments->map(function ($comment) {
            $comment->created_at = $comment->created_at->setTimezone('Europe/Berlin');
            return $comment;
        });
        if ($request->sort_direction === 'asc') {
            $sortDirection = 'asc';
        } else {
            $sortDirection = 'desc';
        }
        return CommentSingleResource::collection($comments->sortBy('id', SORT_REGULAR, $sortDirection));
    }

    /**
     * @param Lead $lead
     */
    public function show(Lead $lead)
    {
        return Response::json($lead->only($lead->getFillable()));
    }

    /**
     * @param Lead    $lead
     * @param Request $request
     */
    public function store(Lead $lead, Request $request)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->fill($request->only('lead_id', 'reason', 'body', 'images'));
        try {
            $date = Carbon::parse($request->date);
        } catch (Exception $e) {
            $date = now('Europe/Berlin');
        }
        $comment->date = $date;
        $lead->comments()->save($comment);
        return Response::json($comment, 201);
    }

    /**
     * Is only supposed to attach images to a comment
     *
     * @param Lead    $lead
     * @param Comment $comment
     * @param Request $request
     */
    public function update(Lead $lead, Comment $comment, Request $request)
    {
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                if ($image instanceof UploadedFile) {
                    $comment->addMedia($image)->toMediaCollection('images');
                }
            }
        }
        $comment->save();
        return Response::json($comment, 200);
    }
}
