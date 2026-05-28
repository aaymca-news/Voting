<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionalElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with(['group', 'votingItems.options'])
            ->where('election_type', 'positional')
            ->latest()
            ->get();

        $groups = Group::orderBy('name')->get();

        return view('positional-elections.index', compact('elections', 'groups'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();

        return view('positional-elections.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id'    => ['required', 'exists:groups,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at'   => ['nullable', 'date'],
            'ends_at'     => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        Election::create([
            'group_id'              => $request->group_id,
            'title'                 => $request->title,
            'description'           => $request->description,
            'starts_at'             => $request->starts_at,
            'ends_at'               => $request->ends_at,
            'election_type'         => 'positional',
            'status'                => 'draft',
            'is_active'             => true,
            'show_results_after_end'=> true,
        ]);

        return redirect()->route('positional-elections.index')
            ->with('success', 'Election created. Now add positions and candidates.');
    }

    public function update(Request $request, Election $election)
    {
        if ($election->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft elections can be edited.');
        }

        $request->validate([
            'group_id'    => ['required', 'exists:groups,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at'   => ['nullable', 'date'],
            'ends_at'     => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $election->update([
            'group_id'    => $request->group_id,
            'title'       => $request->title,
            'description' => $request->description,
            'starts_at'   => $request->starts_at,
            'ends_at'     => $request->ends_at,
        ]);

        return redirect()->back()->with('success', 'Election updated successfully.');
    }

    public function open(Election $election)
    {
        if ($election->votingItems()->doesntExist()) {
            return redirect()->back()->with('error', 'Add at least one position with candidates before opening.');
        }

        DB::transaction(function () use ($election) {
            $election->update([
                'status'    => 'open',
                'starts_at' => $election->starts_at ?? now(),
                'is_active' => true,
            ]);

            $election->votingItems()->update(['status' => 'open']);
        });

        return redirect()->back()->with('success', 'Election opened for voting.');
    }

    public function close(Election $election)
    {
        DB::transaction(function () use ($election) {
            $election->update([
                'status'    => 'closed',
                'ends_at'   => now(),
                'is_active' => false,
            ]);

            $election->votingItems()->update(['status' => 'closed']);
        });

        return redirect()->back()->with('success', 'Election closed.');
    }

    public function destroy(Election $election)
    {
        DB::transaction(function () use ($election) {
            foreach ($election->votingItems as $position) {
                foreach ($position->options as $candidate) {
                    if ($candidate->photo_path) {
                        \Storage::disk('public')->delete($candidate->photo_path);
                    }
                    $candidate->votes()->delete();
                    $candidate->delete();
                }
                $position->delete();
            }
            $election->delete();
        });

        return redirect()->back()->with('success', 'Election deleted.');
    }
}
