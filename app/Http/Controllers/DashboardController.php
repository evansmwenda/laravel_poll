<?php

namespace App\Http\Controllers;

use App\Http\Resources\PollAnswerResource;
use App\Http\Resources\PollResourceDashboard;
use App\Models\Poll;
use App\Models\PollAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Total Number of Surveys
        $total = Poll::query()->where('user_id', $user->id)->count();

        

        // Latest Survey
        $latest = Poll::query()->where('user_id', $user->id)->latest('created_at')->first();
        
        // Total Number of answers
        $totalAnswers = PollAnswer::query()
            ->join('polls', 'poll_answers.poll_id', '=', 'polls.id')
            ->where('polls.user_id', $user->id)
            ->count();
        
        // Latest 5 answer
        $latestAnswers = PollAnswer::query()
            ->join('polls', 'poll_answers.poll_id', '=', 'polls.id')
            ->where('polls.user_id', $user->id)
            ->orderBy('end_date', 'DESC')
            ->limit(5)
            ->getModels('poll_answers.*');

            return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new PollResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => PollAnswerResource::collection($latestAnswers)
        ];
    }
}
