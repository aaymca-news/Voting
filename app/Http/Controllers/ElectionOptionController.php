<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionOption;
use Illuminate\Http\Request;

class ElectionOptionController extends Controller
{
    public function create(Election $election)
    {
        return view('election-options.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        ElectionOption::create([
            'election_id' => $election->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('elections.index')
            ->with('success', 'Candidate/option added successfully.');
    }
}