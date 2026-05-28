<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agenda->title }} — AAYMCA</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f3f4f6; }
        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-icon { font-size: 28px; }
        .topbar-title { font-size: 16px; font-weight: 700; color: #111827; }
        .topbar-meta { font-size: 12px; color: #9ca3af; margin-top: 2px; }
        .topbar-right { display: flex; gap: 10px; }
        .btn-download {
            background: #dc2626;
            color: white;
            padding: 9px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-close {
            background: #f3f4f6;
            color: #374151;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .viewer { height: calc(100vh - 65px); }
        .docx-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 65px);
            padding: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="topbar-left">
            <span class="topbar-icon">{{ $agenda->file_type === 'pdf' ? '📕' : '📝' }}</span>
            <div>
                <div class="topbar-title">{{ $agenda->title }}</div>
                <div class="topbar-meta">{{ $agenda->group->name }} · {{ strtoupper($agenda->file_type) }} · {{ $agenda->created_at->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('agendas.download', $agenda) }}" class="btn-download">
                ⬇ Download
            </a>
            <a href="javascript:window.close()" class="btn-close">✕ Close</a>
        </div>
    </div>

    @if($agenda->file_type === 'pdf')
        <embed src="{{ route('agendas.serve', $agenda) }}"
               type="application/pdf"
               width="100%"
               height="100%"
               class="viewer"
               style="display:block;">
    @else
        <div class="docx-placeholder">
            <p style="font-size:64px; margin-bottom:20px;">📝</p>
            <h2 style="font-size:20px; font-weight:700; color:#111827; margin-bottom:8px;">{{ $agenda->title }}</h2>
            <p style="color:#6b7280; font-size:15px; margin-bottom:6px;">{{ $agenda->group->name }}</p>
            <p style="color:#9ca3af; font-size:14px; margin-bottom:28px;">Word documents cannot be previewed in the browser.</p>
            <a href="{{ route('agendas.download', $agenda) }}"
               style="background:#dc2626; color:white; padding:13px 30px; border-radius:10px; text-decoration:none; font-size:16px; font-weight:700;">
                ⬇ Download to View
            </a>
        </div>
    @endif

</body>
</html>
