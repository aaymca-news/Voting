<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Group;
use App\Models\User;
use App\Models\Vote;
use App\Models\VotingItem;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalVoters = User::where('role', 'voter')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $totalGroups = Group::count();
        $totalElections = Election::count();
        $totalVotingItems = VotingItem::count();
        $totalVotes = Vote::count();

        $openMotions = VotingItem::where('status', 'open')->count();
        $closedMotions = VotingItem::where('status', 'closed')->count();
        $draftMotions = VotingItem::where('status', 'draft')->count();

        return view('dashboard', compact(
            'totalUsers',
            'totalVoters',
            'totalAdmins',
            'totalGroups',
            'totalElections',
            'totalVotingItems',
            'totalVotes',
            'openMotions',
            'closedMotions',
            'draftMotions'
        ));
    }
}