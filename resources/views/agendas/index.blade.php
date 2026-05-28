<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px;">
                <div>
                    <h1 style="font-size:26px; font-weight:800; color:#111827; margin:0;">Meeting Agendas</h1>
                    <p style="color:#6b7280; font-size:14px; margin:4px 0 0 0;">Upload and manage agenda documents for each meeting group.</p>
                </div>
                <a href="{{ route('agendas.create') }}"
                   style="display:inline-block; background:#d97706; color:white; padding:11px 24px; border-radius:8px; text-decoration:none; font-weight:700; font-size:14px;">
                    + Upload Agenda
                </a>
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; color:#166534; border:1px solid #86efac; border-radius:10px; padding:13px 18px; margin-bottom:20px; font-weight:600; font-size:14px;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($agendas->isEmpty())
                <div style="background:white; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:48px; text-align:center;">
                    <p style="font-size:40px; margin:0 0 12px 0;">📄</p>
                    <p style="color:#9ca3af; font-size:16px; margin:0;">No agendas uploaded yet.</p>
                    <a href="{{ route('agendas.create') }}"
                       style="display:inline-block; margin-top:18px; background:#d97706; color:white; padding:10px 22px; border-radius:8px; text-decoration:none; font-weight:700; font-size:14px;">
                        Upload First Agenda
                    </a>
                </div>
            @else
                <div style="background:white; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.07); overflow:hidden;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                                <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.06em;">Title</th>
                                <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.06em;">Meeting</th>
                                <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.06em;">Type</th>
                                <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.06em;">Uploaded</th>
                                <th style="padding:14px 20px; text-align:right; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.06em;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agendas as $agenda)
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:14px 20px;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <span style="font-size:22px;">{{ $agenda->file_type === 'pdf' ? '📕' : '📝' }}</span>
                                            <div>
                                                <p style="font-size:14px; font-weight:600; color:#111827; margin:0;">{{ $agenda->title }}</p>
                                                <p style="font-size:11px; color:#9ca3af; margin:2px 0 0 0;">{{ $agenda->original_filename }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:14px 20px;">
                                        <span style="font-size:14px; color:#374151;">{{ $agenda->group->name }}</span>
                                    </td>
                                    <td style="padding:14px 20px;">
                                        <span style="background:{{ $agenda->file_type === 'pdf' ? '#fee2e2' : '#dbeafe' }}; color:{{ $agenda->file_type === 'pdf' ? '#991b1b' : '#1e40af' }}; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; text-transform:uppercase;">
                                            {{ strtoupper($agenda->file_type) }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px;">
                                        <span style="font-size:13px; color:#6b7280;">{{ $agenda->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td style="padding:14px 20px; text-align:right;">
                                        <div style="display:flex; gap:8px; justify-content:flex-end; align-items:center;">
                                            <a href="{{ route('agendas.preview', $agenda) }}" target="_blank"
                                               style="background:#2563eb; color:white; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:600;">
                                                Preview
                                            </a>
                                            <a href="{{ route('agendas.download', $agenda) }}"
                                               style="background:#dc2626; color:white; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:600;">
                                                Download
                                            </a>
                                            <form method="POST" action="{{ route('agendas.destroy', $agenda) }}"
                                                  onsubmit="return confirm('Delete this agenda? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        style="background:#dc2626; color:white; padding:6px 14px; border-radius:6px; border:none; cursor:pointer; font-size:13px; font-weight:600;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
