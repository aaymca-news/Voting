<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Group;
use App\Models\VotingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with(['group', 'votingItems.options'])->latest()->get();
        $groups = Group::orderBy('name')->get();

        return view('elections.index', compact('elections', 'groups'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();

        return view('elections.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'voting_mode' => ['required', 'in:anonymous,named'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        DB::transaction(function () use ($request) {
            $election = Election::create([
                'group_id' => $request->group_id,
                'title' => $request->title,
                'description' => $request->description,
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'is_active' => true,
                'show_results_after_end' => true,
                'status' => 'draft',
            ]);

            $votingItem = $election->votingItems()->create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => 'motion',
                'status' => 'draft',
                'voting_mode' => $request->voting_mode,
            ]);

            $votingItem->options()->createMany([
                ['name' => 'Yes', 'election_id' => $election->id],
                ['name' => 'No', 'election_id' => $election->id],
                ['name' => 'Abstain', 'election_id' => $election->id],
            ]);
        });

        return redirect()->route('elections.index')->with('success', 'Motion created successfully.');
    }

    public function update(Request $request, Election $election)
    {
        if ($election->status !== 'draft') {
            return redirect()
                ->back()
                ->with('error', 'Only draft motions can be edited.');
        }

        $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'voting_mode' => ['required', 'in:anonymous,named'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        DB::transaction(function () use ($request, $election) {
            $election->update([
                'group_id' => $request->group_id,
                'title' => $request->title,
                'description' => $request->description,
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
            ]);

            $votingItem = $this->ensureVotingItemWithOptions($election);

            $votingItem->update([
                'title' => $request->title,
                'description' => $request->description,
                'voting_mode' => $request->voting_mode,
            ]);
        });

        return redirect()->back()->with('success', 'Motion updated successfully.');
    }

    public function open(Election $election)
    {
        DB::transaction(function () use ($election) {
            $this->ensureVotingItemWithOptions($election);

            $election->update([
                'status' => 'open',
                'starts_at' => $election->starts_at ?? now(),
                'is_active' => true,
            ]);

            $election->votingItems()->update([
                'status' => 'open',
            ]);
        });

        return redirect()->back()->with('success', 'Voting started successfully.');
    }

    public function close(Election $election)
    {
        DB::transaction(function () use ($election) {
            $election->update([
                'status' => 'closed',
                'ends_at' => now(),
                'is_active' => false,
            ]);

            $election->votingItems()->update([
                'status' => 'closed',
            ]);
        });

        return redirect()->back()->with('success', 'Voting closed successfully.');
    }

    public function destroy(Election $election)
    {
        foreach ($election->votingItems as $votingItem) {
            $votingItem->options()->delete();
            $votingItem->delete();
        }

        $election->options()->delete();

        $election->delete();

        return redirect()
            ->back()
            ->with('success', 'Motion deleted successfully.');
    }

    private function ensureVotingItemWithOptions(Election $election): VotingItem
    {
        $votingItem = $election->votingItems()->first();

        if (!$votingItem) {
            $votingItem = $election->votingItems()->create([
                'title' => $election->title,
                'description' => $election->description,
                'type' => 'motion',
                'status' => $election->status,
                'voting_mode' => 'anonymous',
            ]);
        }

        $existingOptions = $votingItem->options()
            ->pluck('name')
            ->map(fn ($name) => strtolower($name))
            ->toArray();

        foreach (['Yes', 'No', 'Abstain'] as $optionName) {
            if (!in_array(strtolower($optionName), $existingOptions)) {
                $votingItem->options()->create([
                    'election_id' => $election->id,
                    'name' => $optionName,
                ]);
            }
        }

        return $votingItem;
    }
}