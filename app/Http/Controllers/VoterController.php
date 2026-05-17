<?php

namespace App\Http\Controllers;

use App\Models\Vote;

class VoterController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $groups = auth()->user()
            ->groups()
            ->with([
                'elections.votingItems' => function ($query) use ($userId) {
                    $query->where(function ($q) use ($userId) {
                        $q->where('status', 'open')
                            ->orWhereHas('votes', function ($voteQuery) use ($userId) {
                                $voteQuery->where('user_id', $userId);
                            });
                    })->with(['options', 'votes']);
                }
            ])
            ->get();

        return view('voter.index', compact('groups'));
    }
}