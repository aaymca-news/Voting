<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$group->users()->where('users.id', $user->id)->exists()) {
            $group->users()->attach($user->id);

            AuditHelper::log(
                'user_added_to_group',
                Group::class,
                $group->id,
                'Added user ' . $user->name . ' (' . $user->email . ') to group: ' . $group->name
            );
        }

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'User added to group successfully.');
    }
}