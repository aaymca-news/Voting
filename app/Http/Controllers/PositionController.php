<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\VotingItem;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function create(Election $election)
    {
        return view('positions.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $request->validate([
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'candidates'                 => ['nullable', 'array'],
            'candidates.*.name'          => ['nullable', 'string', 'max:255'],
            'candidates.*.description'   => ['nullable', 'string', 'max:500'],
            'candidates.*.photo'         => ['nullable', 'image', 'max:2048'],
        ]);

        $position = $election->votingItems()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'type'        => 'candidate',
            'status'      => 'draft',
            'voting_mode' => 'anonymous',
        ]);

        foreach ($request->input('candidates', []) as $index => $data) {
            if (empty($data['name'])) continue;

            $photoPath = null;
            if ($request->hasFile("candidates.$index.photo")) {
                $photoPath = $request->file("candidates.$index.photo")->store('candidates', 'public');
            }

            $position->options()->create([
                'election_id' => $position->election_id,
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'photo_path'  => $photoPath,
            ]);
        }

        return redirect()->route('positional-elections.index')
            ->with('success', 'Position "' . $request->title . '" added.');
    }

    public function update(Request $request, VotingItem $position)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $position->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Position updated.');
    }

    public function open(VotingItem $position)
    {
        if ($position->options()->doesntExist()) {
            return redirect()->back()->with('error', 'Add at least one candidate before opening voting.');
        }

        $position->update(['status' => 'open']);

        return redirect()->back()->with('success', 'Voting opened for "' . $position->title . '".');
    }

    public function close(VotingItem $position)
    {
        $position->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Voting closed for "' . $position->title . '".');
    }

    public function destroy(VotingItem $position)
    {
        foreach ($position->options as $candidate) {
            if ($candidate->photo_path) {
                \Storage::disk('public')->delete($candidate->photo_path);
            }
            $candidate->votes()->delete();
            $candidate->delete();
        }

        $position->delete();

        return redirect()->back()->with('success', 'Position deleted.');
    }
}
