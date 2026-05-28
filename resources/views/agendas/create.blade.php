<x-app-layout>
    <div class="py-8">
        <div style="max-width:600px; margin:0 auto; padding:0 20px;">

            <div style="margin-bottom:24px;">
                <a href="{{ route('agendas.index') }}"
                   style="color:#6b7280; text-decoration:none; font-size:14px;">← Back to Agendas</a>
                <h1 style="font-size:24px; font-weight:800; color:#111827; margin:10px 0 4px 0;">Upload Meeting Agenda</h1>
                <p style="color:#6b7280; font-size:14px; margin:0;">Upload a PDF or Word document and assign it to a meeting group.</p>
            </div>

            @if($errors->any())
                <div style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; border-radius:10px; padding:13px 18px; margin-bottom:20px; font-size:14px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background:white; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px;">
                <form method="POST" action="{{ route('agendas.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Agenda Title <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="e.g. Governance Committee Agenda – June 2026"
                               style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; font-size:14px; box-sizing:border-box; outline:none;"
                               onfocus="this.style.borderColor='#d97706'" onblur="this.style.borderColor='#d1d5db'">
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Assign to Meeting <span style="color:#dc2626;">*</span>
                        </label>
                        <select name="group_id" required
                                style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; font-size:14px; box-sizing:border-box; background:white; outline:none;"
                                onfocus="this.style.borderColor='#d97706'" onblur="this.style.borderColor='#d1d5db'">
                            <option value="">— Select a meeting group —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:28px;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Document File <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="border:2px dashed #d1d5db; border-radius:10px; padding:28px; text-align:center; background:#fafafa;">
                            <p style="font-size:32px; margin:0 0 8px 0;">📎</p>
                            <p style="font-size:14px; color:#6b7280; margin:0 0 12px 0;">PDF or Word document (max 20 MB)</p>
                            <input type="file" name="file" id="file-input" required accept=".pdf,.doc,.docx"
                                   style="display:none;"
                                   onchange="document.getElementById('file-label').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">
                            <label for="file-input"
                                   style="display:inline-block; background:#d97706; color:white; padding:9px 20px; border-radius:8px; cursor:pointer; font-size:13px; font-weight:700;">
                                Choose File
                            </label>
                            <p id="file-label" style="margin:10px 0 0 0; font-size:13px; color:#9ca3af;">No file chosen</p>
                        </div>
                    </div>

                    <button type="submit"
                            style="width:100%; background:#d97706; color:white; padding:13px; border:none; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer;">
                        Upload Agenda
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
