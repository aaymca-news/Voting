<?php

namespace App\Http\Controllers;

class VoterController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $groups = auth()->user()
            ->groups()
            ->with([
                'elections' => function ($query) use ($userId) {
                    $query->where(function ($q) use ($userId) {
                        $q->where('status', 'open')
                            ->orWhereHas('votingItems.votes', function ($voteQuery) use ($userId) {
                                $voteQuery->where('user_id', $userId);
                            });
                    })->with(['votingItems.options', 'votingItems.votes']);
                }
            ])
            ->get();

        return view('voter.index', compact('groups'));
    }
}