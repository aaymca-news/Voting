<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\MeetingAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingAgendaController extends Controller
{
    public function index()
    {
        $agendas = MeetingAgenda::with('group')->latest()->get();
        return view('agendas.index', compact('agendas'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();
        return view('agendas.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'group_id' => 'required|exists:groups,id',
            'file'     => 'required|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = uniqid('agenda_') . '.' . $extension;

        $file->storeAs('agendas', $filename);

        MeetingAgenda::create([
            'group_id'          => $request->group_id,
            'uploaded_by'       => auth()->id(),
            'title'             => $request->title,
            'file_path'         => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_type'         => $extension === 'pdf' ? 'pdf' : 'docx',
        ]);

        return redirect()->route('agendas.index')->with('success', 'Agenda uploaded successfully.');
    }

    public function preview(MeetingAgenda $agenda)
    {
        $this->authorizeAccess($agenda);
        return view('agendas.preview', compact('agenda'));
    }

    public function serve(MeetingAgenda $agenda)
    {
        $this->authorizeAccess($agenda);

        $path = storage_path('app/agendas/' . $agenda->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        $mimeType = $agenda->file_type === 'pdf'
            ? 'application/pdf'
            : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

        return response()->file($path, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $agenda->original_filename . '"',
        ]);
    }

    public function download(MeetingAgenda $agenda)
    {
        $this->authorizeAccess($agenda);

        $path = storage_path('app/agendas/' . $agenda->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->download($path, $agenda->original_filename);
    }

    public function destroy(MeetingAgenda $agenda)
    {
        Storage::delete('agendas/' . $agenda->file_path);
        $agenda->delete();

        return redirect()->route('agendas.index')->with('success', 'Agenda deleted successfully.');
    }

    private function authorizeAccess(MeetingAgenda $agenda): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        $isMember = $user->groups()->where('groups.id', $agenda->group_id)->exists();

        if (!$isMember) {
            abort(403, 'You do not have access to this agenda.');
        }
    }
}
