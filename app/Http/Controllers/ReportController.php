<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $groups = Group::with([
            'elections.votingItems.options.votes.user',
        ])->latest()->get();

        return view('reports.index', compact('groups'));
    }

    public function groupPdf(Group $group)
    {
        $group->load([
            'elections.votingItems.options.votes.user',
        ]);

        $pdf = Pdf::loadView('reports.group-pdf', compact('group'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('group-report-' . $group->id . '.pdf');
    }
}