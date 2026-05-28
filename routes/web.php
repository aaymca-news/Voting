<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionOptionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\PositionalElectionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VotingItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/voter', [VoterController::class, 'index'])->name('voter.index');

    Route::middleware(['admin'])->group(function () {

        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
        Route::patch('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

        Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])
            ->name('group-members.store');

        Route::delete('/groups/{group}/members/{user}', [GroupMemberController::class, 'destroy'])
            ->name('group-members.destroy');

        Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
        Route::get('/elections/create', [ElectionController::class, 'create'])->name('elections.create');
        Route::post('/elections', [ElectionController::class, 'store'])->name('elections.store');
        Route::patch('/elections/{election}', [ElectionController::class, 'update'])->name('elections.update');
        Route::patch('/elections/{election}/open', [ElectionController::class, 'open'])->name('elections.open');
        Route::patch('/elections/{election}/close', [ElectionController::class, 'close'])->name('elections.close');
        Route::delete('/elections/{election}', [ElectionController::class, 'destroy'])->name('elections.destroy');

        Route::get('/elections/{election}/voting-items/create', [VotingItemController::class, 'create'])
            ->name('voting-items.create');

        Route::post('/elections/{election}/voting-items', [VotingItemController::class, 'store'])
            ->name('voting-items.store');

        Route::get('/voting-items/{votingItem}/edit', [VotingItemController::class, 'edit'])
            ->name('voting-items.edit');

        Route::patch('/voting-items/{votingItem}', [VotingItemController::class, 'update'])
            ->name('voting-items.update');

        Route::delete('/voting-items/{votingItem}', [VotingItemController::class, 'destroy'])
            ->name('voting-items.destroy');

        Route::patch('/voting-items/{votingItem}/open', [VotingItemController::class, 'open'])
            ->name('voting-items.open');

        Route::patch('/voting-items/{votingItem}/close', [VotingItemController::class, 'close'])
            ->name('voting-items.close');

        Route::get('/elections/{election}/options/create', [ElectionOptionController::class, 'create'])
            ->name('election-options.create');

        Route::post('/elections/{election}/options', [ElectionOptionController::class, 'store'])
            ->name('election-options.store');

        Route::get('/votes', [VoteController::class, 'index'])->name('votes.index');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.make-admin');
        Route::patch('/users/{user}/remove-admin', [UserController::class, 'removeAdmin'])->name('users.remove-admin');

        // Positional Elections
        Route::get('/positional-elections', [PositionalElectionController::class, 'index'])->name('positional-elections.index');
        Route::get('/positional-elections/create', [PositionalElectionController::class, 'create'])->name('positional-elections.create');
        Route::post('/positional-elections', [PositionalElectionController::class, 'store'])->name('positional-elections.store');
        Route::patch('/positional-elections/{election}', [PositionalElectionController::class, 'update'])->name('positional-elections.update');
        Route::patch('/positional-elections/{election}/open', [PositionalElectionController::class, 'open'])->name('positional-elections.open');
        Route::patch('/positional-elections/{election}/close', [PositionalElectionController::class, 'close'])->name('positional-elections.close');
        Route::delete('/positional-elections/{election}', [PositionalElectionController::class, 'destroy'])->name('positional-elections.destroy');

        // Positions (within positional elections)
        Route::get('/positional-elections/{election}/positions/create', [PositionController::class, 'create'])->name('positions.create');
        Route::post('/positional-elections/{election}/positions', [PositionController::class, 'store'])->name('positions.store');
        Route::patch('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
        Route::patch('/positions/{position}/open', [PositionController::class, 'open'])->name('positions.open');
        Route::patch('/positions/{position}/close', [PositionController::class, 'close'])->name('positions.close');
        Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
        Route::get('/positions/{position}/results', [VoteController::class, 'positionResults'])->name('positions.results');

        // Candidates (within positions)
        Route::get('/positions/{position}/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
        Route::post('/positions/{position}/candidates', [CandidateController::class, 'store'])->name('candidates.store');
        Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/groups/{group}/pdf', [ReportController::class, 'groupPdf'])
            ->name('reports.group.pdf');

        Route::get('/elections/{election}/results/pdf', [VoteController::class, 'exportPdf'])
            ->name('votes.results.pdf');
    });

    Route::get('/elections/{election}/vote', [VoteController::class, 'show'])
        ->name('votes.show');

    Route::post('/elections/{election}/vote', [VoteController::class, 'store'])
        ->name('votes.store');

    Route::get('/elections/{election}/results', [VoteController::class, 'results'])
        ->name('votes.results');
});

require __DIR__.'/auth.php';