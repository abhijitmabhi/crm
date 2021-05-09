<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Admin;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class AgentController extends Controller
{
    const WEEKDAYS = ['mo', 'tu', 'we', 'th', 'fr'];

    /**
     * Default number agents per page
     */
    const PER_PAGE = 15;

    public function __invoke(Request $request)
    {
        $startDate = self::getStartDate($request->startDate);
        $agents = self::findRelevantAgents($request);
        $userList = [];
        foreach ($agents as $agent) {
            $userList[] = self::generateListItem($agent, $startDate);
        }
        return view('callcenter.AgentSuccessTable', [
            'agents' => $agents,
            'users' => $userList,
            'userName' => $request->user_name,
            'startDate' => $startDate->format('d.m.y'),
            'endDate' => $startDate->endOfWeek()->format('d.m.y')
        ]);
    }

    protected static function getStartDate($date) : CarbonImmutable
    {
        try {
            return Carbon::parse($date)->startOfWeek()->toImmutable();
        } catch (Exception $e) {
            return Carbon::now('Europe/Berlin')->startOfWeek()->toImmutable();
        }
    }

    protected static function findRelevantAgents(Request $request) : LengthAwarePaginator
    {
        $usersQuery = User::byRole('callcenter-agent')
        ->orderBy('last_name');
        $per_page = $request->per_page ?? self::PER_PAGE;
        if ($request->user_name) {
            $parts = explode(" ", $request->user_name);
            foreach ($parts as $part) {
                $usersQuery->where('first_name', 'LIKE', "%$part%");
            }
        }
        return $usersQuery->paginate($per_page, ['id', 'first_name', 'last_name', 'options']);
    }

    protected static function generateListItem(User $agent, CarbonInterface $startDate) : array
    {
        $listItem = [
            'id'   => $agent->id,
            'name' => $agent->formalName,
            'recalls' => Lead::recall()->where('agent_id', $agent->id)->count(),
            'dialer_active' => $agent->get_option('dialer_active', false)
        ];
        $comments = self::findRelevantComments($agent, $startDate);
        foreach (self::WEEKDAYS as $index => $day) {
            $relevantComments = $comments->filter(function ($comment) use ($startDate, $index) {
                $currentDay = $startDate->addDays($index);
                return $currentDay->isSameDay($comment->created_at);
            });
            $listItem[$day] = $relevantComments->count();
        }
        return $listItem;
    }

    protected static function findRelevantComments(User $agent, CarbonInterface $startDate) : Collection
    {
        return $agent->comments()
            ->where('reason', 'LIKE', CommentReason::APPOINTMENT)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $startDate->addDays(count(self::WEEKDAYS) - 1))
            ->get(['created_at']);
    }
}
