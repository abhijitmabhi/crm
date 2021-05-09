<?php
namespace LocalheroPortal\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Callcenter\Http\Resources\CommentSingleResource;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class UserCommentController extends Controller
{
    /**
     * @param User    $user
     * @param Request $request
     */
    public function index(User $user, Request $request)
    {
        if ($request->start && $request->end) {
            return CommentSingleResource::collection($user->comments->whereBetween('created_at', [$request->start, $request->end]));
        }
        return CommentSingleResource::collection($user->comments);
    }

    /**
     * @param User $user
     * @param int  $id
     */
    public function show(User $user, int $id)
    {
        return Response::json($user->comments->where('id', $id));
    }
}
