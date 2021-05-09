<?php


namespace LocalheroPortal\Core\Repository;


use Illuminate\Support\Facades\DB;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;

class CommentRepository
{

    public function getCountByCallReasonAndUser($startDate, $endDate)
    {
        $query = Comment::query()
            ->select(DB::raw("
                user_id,
                created_at,
                COUNT(*) AS total,
                COUNT(CASE WHEN reason = '".CommentReason::APPOINTMENT."' THEN 1 ELSE null END) AS appointment,
                COUNT(CASE WHEN reason = '".CommentReason::BLACKLIST."' THEN 1 ELSE null END) AS blacklist,
                COUNT(CASE WHEN reason = '".CommentReason::NOT_REACHED."' THEN 1 ELSE null END) AS notReached,
                COUNT(CASE WHEN reason = '".CommentReason::NO_INTEREST."' THEN 1 ELSE null END) AS noInterest,
                COUNT(CASE WHEN reason = '".CommentReason::RECALL."' THEN 1 ELSE null END) AS recall
                "))
            ->whereIn('reason', [
                CommentReason::APPOINTMENT, CommentReason::BLACKLIST, CommentReason::NOT_REACHED,
                CommentReason::NO_INTEREST, CommentReason::RECALL
            ]);

        if ($startDate) {
            $query = $query->where('created_at', '>=', $startDate->toDateString());
        }
        if ($endDate) {
            $query = $query->where('created_at', '<=', $endDate->toDateString());
        }

        return $query
            ->groupBy('user_id')
            ->get();

    }

}