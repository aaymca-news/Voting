<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account while logged in.');
        }

        $user->groups()->detach();

        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'User account removed successfully.');
    }
}