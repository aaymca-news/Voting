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
            'name' => ['required'],
            'description' => ['nullable'],
        ]);

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => strtolower(str_replace(' ', '-', $request->name)) . '-' . substr(md5(time()), 0, 5),
        ]);

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    public function show(Group $group)
    {
        $group->load([
            'elections.votingItems.options',
            'users'
        ]);

        $users = User::where('role', 'voter')
            ->orderBy('name')
            ->get();

        return view('groups.show', compact(
            'group',
            'users'
        ));
    }

    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $group->users()->syncWithoutDetaching([
            $request->user_id
        ]);

        return redirect()
            ->back()
            ->with('success', 'User added to group successfully.');
    }
}