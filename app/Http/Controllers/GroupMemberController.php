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
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['required', 'exists:users,id', 'distinct'],
        ]);

        $existingUserIds = $group->users()
            ->pluck('users.id')
            ->toArray();

        $newUserIds = collect($request->user_ids)
            ->map(fn ($userId) => (int) $userId)
            ->diff($existingUserIds)
            ->values()
            ->toArray();

        if (empty($newUserIds)) {
            return redirect()
                ->route('groups.show', $group)
                ->with('error', 'Selected users are already in this meeting.');
        }

        $group->users()->attach($newUserIds);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Users added to meeting successfully.');
    }

    public function destroy(Group $group, User $user): RedirectResponse
    {
        $group->users()->detach($user->id);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'User removed from group successfully.');
    }
}