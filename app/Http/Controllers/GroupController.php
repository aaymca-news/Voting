<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::latest()->get();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => str($request->name)->slug() . '-' . strtolower(str()->random(5)),
        ]);

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    public function show(Group $group)
    {
        $group->load([
            'users',
            'elections.votingItems',
            'agendas',
        ]);

        $users = User::whereNotIn('role', ['admin', 'super_admin'])
            ->orderBy('name')
            ->get();

        $groups = Group::orderBy('name')->get();

        return view('groups.show', compact('group', 'users', 'groups'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->users()->detach();

        foreach ($group->elections as $election) {
            foreach ($election->votingItems as $votingItem) {
                $votingItem->options()->delete();
                $votingItem->delete();
            }

            $election->options()->delete();
            $election->delete();
        }

        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group deleted successfully.');
    }
}