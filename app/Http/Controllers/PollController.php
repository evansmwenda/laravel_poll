<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Http\Requests\UpdatePollRequest;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use Illuminate\Support\Facades\Request;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return PollResource::collection(Poll::where('user_id',$user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request)
    {
        $result = Poll::create($request->validated());
        return new PollResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Poll $poll,Request $request)
    {
        $user = $request->user();
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        return new PollResource($poll);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollRequest $request, Poll $poll)
    {
        $poll->update($request->validated());
        return new PollResource($poll);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll,Request $request)
    {
        $user = $request->user();
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        $poll->delete();
        return response('',204);
    }
}
