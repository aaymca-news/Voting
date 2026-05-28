<?php

namespace App\Http\Controllers;

use App\Models\ElectionOption;
use App\Models\VotingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function create(VotingItem $position)
    {
        return view('candidates.create', compact('position'));
    }

    public function store(Request $request, VotingItem $position)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'photo'       => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        $position->options()->create([
            'election_id'   => $position->election_id,
            'name'          => $request->name,
            'description'   => $request->description,
            'photo_path'    => $photoPath,
        ]);

        return redirect()->route('positional-elections.index')
            ->with('success', 'Candidate "' . $request->name . '" added.');
    }

    public function destroy(ElectionOption $candidate)
    {
        $position = $candidate->votingItem;

        if ($position && $position->status !== 'draft') {
            return redirect()->back()->with('error', 'Candidates can only be removed before voting begins.');
        }

        if ($candidate->photo_path) {
            Storage::disk('public')->delete($candidate->photo_path);
        }

        $candidate->votes()->delete();
        $candidate->delete();

        return redirect()->back()->with('success', 'Candidate removed.');
    }
}
