<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Group;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with('group')->latest()->get();

        return view('elections.index', compact('elections'));
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
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        Election::create([
            'group_id' => $request->group_id,
            'title' => $request->title,
            'description' => $request->description,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => true,
            'show_results_after_end' => true,
            'status' => $request->status ?? 'draft',
        ]);

        return redirect()->route('elections.index')->with('success', 'Election created successfully.');
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $election->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('elections.index')->with('success', 'Election updated successfully.');
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
            ->route('elections.index')
            ->with('success', 'Election deleted successfully.');
    }
}