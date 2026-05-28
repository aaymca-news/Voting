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
                'agendas',
                'elections' => function ($query) use ($userId) {
                    $query->where(function ($q) use ($userId) {
                        // Motions that are open
                        $q->where(function ($inner) {
                            $inner->where('election_type', 'motion')->where('status', 'open');
                        })
                        // Positional elections with at least one open position
                        ->orWhere(function ($inner) {
                            $inner->where('election_type', 'positional')
                                ->whereHas('votingItems', fn ($vq) => $vq->where('status', 'open'));
                        })
                        // Any election where user has already voted (show their record)
                        ->orWhereHas('votingItems.votes', fn ($vq) => $vq->where('user_id', $userId));
                    })
                    ->with([
                        'votingItems' => function ($vq) use ($userId) {
                            // Load positions/motions that are open OR the user has voted on
                            $vq->where(function ($inner) use ($userId) {
                                $inner->where('status', 'open')
                                    ->orWhereHas('votes', fn ($voteQ) => $voteQ->where('user_id', $userId));
                            })->with([
                                'options',
                                'votes' => fn ($vq) => $vq->where('user_id', $userId)->with('electionOption'),
                            ]);
                        },
                    ]);
                },
            ])
            ->get();

        return view('voter.index', compact('groups'));
    }
}
