<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function store(Request $request, Group $group): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$group->users()->where('users.id', $user->id)->exists()) {
            $group->users()->attach($user->id);
        }

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'User added to group successfully.');
    }

    public function destroy(Group $group, User $user): RedirectResponse
    {
        $group->users()->detach($user->id);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'User removed from group successfully.');
    }
}