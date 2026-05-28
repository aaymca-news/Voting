<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;
use App\Models\VotingItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VoteController extends Controller
{
    public function index()
    {
        $elections = Election::with(['group', 'votingItems.options.votes'])->latest()->get();

        $totalElections = $elections->count();

        $totalVotingItems = VotingItem::count();

        $totalVotes = Vote::count();

        return view('votes.index', compact(
            'elections',
            'totalElections',
            'totalVotingItems',
            'totalVotes'
        ));
    }

    public function show(Election $election)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()
                ->route('votes.index')
                ->with('error', 'Admins do not vote. Voting is only available on the voter portal.');
        }

        $election->load('votingItems.options');

        return view('votes.show', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Admins cannot vote.');
        }

        $request->validate([
            'election_option_id' => ['required', 'exists:election_options,id'],
            'voting_item_id'     => ['required', 'exists:voting_items,id'],
        ]);

        $votingItem = VotingItem::findOrFail($request->voting_item_id);

        if ($votingItem->election_id !== $election->id) {
            return redirect()->back()->with('error', 'Invalid voting item.');
        }

        // Motions: check election-level status; positional elections: check position-level status
        if ($election->isMotion() && $election->status !== 'open') {
            return redirect()->back()->with('error', 'Voting is closed for this motion.');
        }

        if ($election->isPositional() && $votingItem->status !== 'open') {
            return redirect()->back()->with('error', 'Voting is closed for this position.');
        }

        $optionBelongsToItem = $votingItem->options()
            ->where('id', $request->election_option_id)
            ->exists();

        if (!$optionBelongsToItem) {
            return redirect()->back()->with('error', 'Invalid voting option.');
        }

        $alreadyVoted = Vote::where('user_id', auth()->id())
            ->where('voting_item_id', $votingItem->id)
            ->exists();

        if ($alreadyVoted) {
            $msg = $election->isPositional()
                ? 'You have already voted for this position.'
                : 'You have already voted on this motion.';
            return redirect()->back()->with('error', $msg);
        }

        Vote::create([
            'user_id'            => auth()->id(),
            'election_id'        => $election->id,
            'election_option_id' => $request->election_option_id,
            'voting_item_id'     => $votingItem->id,
        ]);

        $success = $election->isPositional()
            ? 'Vote submitted successfully for this elective position.'
            : 'Vote submitted successfully.';

        return redirect()->back()->with('success', $success);
    }

    public function results(Election $election)
    {
        $election->load('votingItems.options.votes.user');

        return view('votes.results', compact('election'));
    }

    public function positionResults(VotingItem $position)
    {
        $position->load(['options.votes.user', 'election.group']);

        return view('votes.position-results', compact('position'));
    }

    public function exportPdf(Election $election)
    {
        $election->load('votingItems.options.votes.user');

        $pdf = Pdf::loadView('votes.pdf-results', compact('election'));

        return $pdf->download('results-' . $election->id . '.pdf');
    }
}