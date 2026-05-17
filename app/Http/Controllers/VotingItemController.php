<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Election;
use App\Models\VotingItem;
use Illuminate\Http\Request;

class VotingItemController extends Controller
{
    public function create(Election $election)
    {
        return view('voting-items.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'voting_mode' => ['required', 'in:anonymous,named'],
        ]);

        $votingItem = VotingItem::create([
            'election_id' => $election->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'draft',
            'voting_mode' => $request->voting_mode,
        ]);

        $votingItem->options()->createMany([
            ['name' => 'Yes', 'election_id' => $election->id],
            ['name' => 'No', 'election_id' => $election->id],
            ['name' => 'Abstain', 'election_id' => $election->id],
        ]);

        AuditHelper::log(
            'motion_created',
            VotingItem::class,
            $votingItem->id,
            'Created motion/agendum: ' . $votingItem->title . ' (' . $votingItem->voting_mode . ' voting)'
        );

        return redirect()
            ->route('groups.show', $election->group_id)
            ->with('success', 'Motion / Agenda created successfully.');
    }

    public function edit(VotingItem $votingItem)
    {
        return view('voting-items.edit', compact('votingItem'));
    }

    public function update(Request $request, VotingItem $votingItem)
    {
        if ($votingItem->status !== 'draft') {
            return redirect()
                ->back()
                ->with('error', 'Only draft motions can be edited.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'voting_mode' => ['required', 'in:anonymous,named'],
        ]);

        $oldTitle = $votingItem->title;
        $oldMode = $votingItem->voting_mode;

        $votingItem->update([
            'title' => $request->title,
            'description' => $request->description,
            'voting_mode' => $request->voting_mode,
        ]);

        AuditHelper::log(
            'motion_updated',
            VotingItem::class,
            $votingItem->id,
            'Updated motion/agendum from "' . $oldTitle . '" to "' . $votingItem->title . '". Voting mode changed from ' . $oldMode . ' to ' . $votingItem->voting_mode . '.'
        );

        return redirect()
            ->route('groups.show', $votingItem->election->group_id)
            ->with('success', 'Motion / Agenda updated successfully.');
    }

    public function open(VotingItem $votingItem)
    {
        $votingItem->update([
            'status' => 'open',
        ]);

        AuditHelper::log(
            'vote_opened',
            VotingItem::class,
            $votingItem->id,
            'Opened voting for motion/agendum: ' . $votingItem->title
        );

        return redirect()
            ->back()
            ->with('success', 'Motion vote opened successfully.');
    }

    public function close(VotingItem $votingItem)
    {
        $votingItem->update([
            'status' => 'closed',
        ]);

        AuditHelper::log(
            'vote_closed',
            VotingItem::class,
            $votingItem->id,
            'Closed voting for motion/agendum: ' . $votingItem->title
        );

        return redirect()
            ->back()
            ->with('success', 'Motion vote closed successfully.');
    }

    public function destroy(VotingItem $votingItem)
{
    if ($votingItem->status !== 'draft') {
        return redirect()
            ->back()
            ->with('error', 'Only draft motions can be deleted.');
    }

    AuditHelper::log(
        'motion_deleted',
        VotingItem::class,
        $votingItem->id,
        'Deleted motion/agendum: ' . $votingItem->title
    );

    $votingItem->options()->delete();

    $votingItem->delete();

    return redirect()
        ->back()
        ->with('success', 'Motion / Agenda deleted successfully.');
}

}