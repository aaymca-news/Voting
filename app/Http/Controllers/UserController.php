<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    private string $superAdminEmail = 'raymondmunene5@gmail.com';

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

        if ($user->email === $this->superAdminEmail) {
            return redirect()
                ->back()
                ->with('error', 'The super admin account cannot be removed.');
        }

        $user->groups()->detach();

        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'User account removed successfully.');
    }

    public function makeAdmin(User $user): RedirectResponse
    {
        if (auth()->user()->email !== $this->superAdminEmail) {
            return redirect()
                ->back()
                ->with('error', 'Only the super admin can make users admins.');
        }

        $user->role = 'admin';
        $user->save();

        return redirect()
            ->back()
            ->with('success', $user->name . ' is now an admin.');
    }

    public function removeAdmin(User $user): RedirectResponse
    {
        if (auth()->user()->email !== $this->superAdminEmail) {
            return redirect()
                ->back()
                ->with('error', 'Only the super admin can remove admin access.');
        }

        if ($user->email === $this->superAdminEmail) {
            return redirect()
                ->back()
                ->with('error', 'You cannot remove the super admin role.');
        }

        $user->role = 'voter';
        $user->save();

        return redirect()
            ->back()
            ->with('success', $user->name . ' is no longer an admin.');
    }
}