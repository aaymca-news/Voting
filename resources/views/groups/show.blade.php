<x-app-layout>
    <div style="background:#f1f5f9; min-height:100vh; padding:32px 0 56px;">
        <div style="max-width:1100px; margin:0 auto; padding:0 24px;">

            {{-- HEADER --}}
            <div style="margin-bottom:28px;">
                <a href="{{ route('groups.index') }}"
                   style="font-size:12px; color:#94a3b8; text-decoration:none; font-weight:600;">← Meetings</a>
                <div style="display:flex; align-items:center; gap:12px; margin-top:8px; flex-wrap:wrap;">
                    <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">{{ $group->name }}</h1>
                    <span style="background:#e2e8f0; color:#475569; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px; letter-spacing:0.04em;">{{ $group->code }}</span>
                </div>
                @if($group->description)
                    <p style="font-size:13px; color:#94a3b8; margin:4px 0 0;">{{ $group->description }}</p>
                @endif
            </div>

            {{-- ══ MEETING AGENDAS ══ --}}
            <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:20px;">

                <div style="display:flex; justify-content:space-between; align-items:center; padding:14px 20px; border-bottom:1px solid #fde68a; background:#fffbeb; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-size:16px;">📄</span>
                        <span style="font-size:13px; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:0.07em;">Meeting Agendas</span>
                    </div>
                    <a href="{{ route('agendas.create', ['group_id' => $group->id]) }}"
                       style="background:#d97706; color:white; padding:6px 14px; border-radius:7px; text-decoration:none; font-size:12px; font-weight:700;">
                        + Upload Agenda
                    </a>
                </div>

                <div style="padding:14px 20px;">
                    @forelse($group->agendas as $agenda)
                        <div style="display:flex; align-items:center; justify-content:space-between; border:1px solid #fde68a; border-radius:9px; padding:10px 14px; margin-bottom:8px; gap:10px; flex-wrap:wrap; background:#fffdf5;">
                            <div style="display:flex; align-items:center; gap:10px; min-width:0; flex:1;">
                                <span style="font-size:18px; flex-shrink:0;">{{ $agenda->file_type === 'pdf' ? '📕' : '📝' }}</span>
                                <div style="min-width:0;">
                                    <p style="margin:0; font-weight:600; color:#111827; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $agenda->title }}</p>
                                    <p style="margin:1px 0 0; font-size:11px; color:#9ca3af;">{{ strtoupper($agenda->file_type) }} · {{ $agenda->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div style="display:flex; gap:6px; flex-shrink:0;">
                                <a href="{{ route('agendas.preview', $agenda) }}" target="_blank"
                                   style="background:#d97706; color:white; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:600;">Preview</a>
                                <a href="{{ route('agendas.download', $agenda) }}"
                                   style="background:#dc2626; color:white; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:600;">Download</a>
                                <button type="button"
                                        onclick="document.getElementById('delete-agenda-box-{{ $agenda->id }}').style.display='flex'"
                                        style="background:#f1f5f9; color:#64748b; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; border:1px solid #e2e8f0; cursor:pointer;">
                                    Delete
                                </button>
                            </div>
                        </div>

                        {{-- DELETE AGENDA MODAL --}}
                        <div id="delete-agenda-box-{{ $agenda->id }}"
                             style="display:none;"
                             class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                                <h2 class="text-xl font-bold text-gray-800 mb-3">Delete Agenda</h2>
                                <p class="text-gray-600 text-sm mb-6">Delete "<strong>{{ $agenda->title }}</strong>"? This cannot be undone.</p>
                                <div class="flex justify-end gap-3">
                                    <button type="button"
                                            onclick="document.getElementById('delete-agenda-box-{{ $agenda->id }}').style.display='none'"
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                        Cancel
                                    </button>
                                    <form method="POST" action="{{ route('agendas.destroy', $agenda) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                            Yes, Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#94a3b8; font-size:13px; margin:0;">No agendas uploaded yet.</p>
                    @endforelse
                </div>

            </div>

            {{-- ══ MEETING MEMBERS (collapsible) ══ --}}
            <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:20px;">

                {{-- Toggle header --}}
                <button type="button"
                        onclick="toggleSection('members-body', 'members-chevron')"
                        style="width:100%; display:flex; justify-content:space-between; align-items:center; padding:14px 20px; background:none; border:none; cursor:pointer; text-align:left; border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; border-radius:8px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:15px;">👥</div>
                        <div>
                            <span style="font-size:14px; font-weight:700; color:#0f172a;">Meeting Members</span>
                            <span style="font-size:12px; color:#94a3b8; margin-left:8px;">{{ $group->users->count() }} {{ Str::plural('member', $group->users->count()) }}</span>
                        </div>
                    </div>
                    <span id="members-chevron" style="font-size:12px; color:#94a3b8; transition:transform 0.2s;">▼</span>
                </button>

                {{-- Collapsible body --}}
                <div id="members-body">

                    {{-- Add member form --}}
                    <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                        <form method="POST"
                              action="{{ route('group-members.store', $group) }}"
                              style="display:flex; gap:10px; align-items:flex-start; flex-wrap:wrap;">
                            @csrf

                            @php $memberIds = $group->users->pluck('id')->toArray(); @endphp

                            <div style="position:relative; flex:1; min-width:260px;">
                                <button type="button"
                                        onclick="document.getElementById('users-dropdown-{{ $group->id }}').style.display = document.getElementById('users-dropdown-{{ $group->id }}').style.display === 'block' ? 'none' : 'block'"
                                        style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px 14px; background:white; text-align:left; display:flex; justify-content:space-between; align-items:center; font-size:13px; color:#374151; cursor:pointer;">
                                    <span id="selected-users-label-{{ $group->id }}">Select users to add</span>
                                    <span style="color:#94a3b8; font-size:11px;">▼</span>
                                </button>

                                <div id="users-dropdown-{{ $group->id }}"
                                     style="display:none; position:absolute; top:42px; left:0; right:0; background:white; border:1px solid #d1d5db; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.1); padding:8px; z-index:40; max-height:240px; overflow-y:auto;">
                                    @foreach($users as $user)
                                        @php $alreadyAdded = in_array($user->id, $memberIds); @endphp
                                        <label style="display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:7px; cursor:{{ $alreadyAdded ? 'not-allowed' : 'pointer' }}; color:{{ $alreadyAdded ? '#9ca3af' : '#111827' }}; background:{{ $alreadyAdded ? '#f8fafc' : 'white' }}; font-size:13px;">
                                            <input type="checkbox"
                                                   name="user_ids[]"
                                                   value="{{ $user->id }}"
                                                   class="meeting-user-checkbox-{{ $group->id }}"
                                                   onchange="updateSelectedUsers{{ $group->id }}()"
                                                   @if($alreadyAdded) disabled @endif>
                                            <span>{{ $user->name }}
                                                @if($alreadyAdded)
                                                    <span style="font-size:11px; color:#94a3b8;">— already added</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit"
                                    style="background:#2563eb; color:white; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-size:13px; font-weight:600; white-space:nowrap;">
                                Add Members
                            </button>
                        </form>
                    </div>

                    {{-- Members list --}}
                    <div style="padding:8px 0;">
                        @forelse($group->users as $user)
                            <div style="padding:10px 20px; border-bottom:1px solid #f8fafc;">
                                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:32px; height:32px; border-radius:50%; background:#e0e7ff; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; color:#4f46e5; font-weight:700;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p style="font-size:13px; font-weight:600; color:#0f172a; margin:0;">{{ $user->name }}</p>
                                            <p style="font-size:11px; color:#94a3b8; margin:0;">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span style="background:#dcfce7; color:#15803d; font-size:10px; font-weight:700; padding:2px 8px; border-radius:999px;">Voter</span>
                                        <button type="button"
                                                onclick="document.getElementById('remove-member-box-{{ $user->id }}').style.display='block'"
                                                style="background:#f1f5f9; color:#64748b; padding:4px 10px; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer; font-size:11px; font-weight:600;">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                {{-- Remove confirmation --}}
                                <div id="remove-member-box-{{ $user->id }}"
                                     style="display:none; margin-top:10px; background:#fef2f2; border:1px solid #fecaca; border-radius:9px; padding:14px 16px;">
                                    <p style="font-size:13px; font-weight:600; color:#991b1b; margin:0 0 4px;">Remove {{ $user->name }}?</p>
                                    <p style="font-size:12px; color:#b91c1c; margin:0 0 12px;">They will lose access to this meeting's votes and agendas.</p>
                                    <div style="display:flex; gap:8px;">
                                        <form method="POST" action="{{ route('group-members.destroy', [$group, $user]) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    style="background:#dc2626; color:white; padding:6px 14px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                Yes, Remove
                                            </button>
                                        </form>
                                        <button type="button"
                                                onclick="document.getElementById('remove-member-box-{{ $user->id }}').style.display='none'"
                                                style="background:#f1f5f9; color:#374151; padding:6px 14px; border-radius:7px; border:1px solid #e2e8f0; cursor:pointer; font-size:12px; font-weight:600;">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:13px; padding:16px 20px; margin:0;">No members added yet.</p>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- ══ ELECTIONS / MOTIONS ══ --}}
            <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden;">

                <div style="padding:14px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:8px;">
                    <div style="width:32px; height:32px; border-radius:8px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:15px;">✋</div>
                    <span style="font-size:14px; font-weight:700; color:#0f172a;">Elections &amp; Motions</span>
                </div>

                <div style="padding:8px 0;">

                    @forelse($group->elections as $election)

                        @if($election->isMotion())

                            {{-- MOTION ROW --}}
                            <div style="padding:12px 20px; border-bottom:1px solid #f8fafc;">
                                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">

                                    <div style="flex:1; min-width:0;">
                                        <div style="display:flex; align-items:center; gap:7px; flex-wrap:wrap; margin-bottom:2px;">
                                            <span style="font-size:10px; font-weight:700; background:#f1f5f9; color:#64748b; padding:2px 7px; border-radius:999px; text-transform:uppercase; letter-spacing:0.05em;">Motion</span>
                                            <span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $election->title }}</span>
                                            @if($election->status === 'open')
                                                <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Open</span>
                                            @elseif($election->status === 'closed')
                                                <span style="background:#f1f5f9; color:#64748b; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Closed</span>
                                            @else
                                                <span style="background:#fef9c3; color:#854d0e; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Draft</span>
                                            @endif
                                        </div>
                                        @if($election->description)
                                            <p style="font-size:12px; color:#94a3b8; margin:0;">{{ $election->description }}</p>
                                        @endif
                                    </div>

                                    <div style="display:flex; gap:6px; flex-shrink:0;">
                                        @if($election->status === 'draft')
                                            <button type="button"
                                                    onclick="document.getElementById('edit-group-election-box-{{ $election->id }}').style.display='flex'"
                                                    style="background:#f59e0b; color:white; padding:5px 11px; border-radius:6px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('elections.open', $election) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        style="background:#16a34a; color:white; padding:5px 11px; border-radius:6px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                    Start
                                                </button>
                                            </form>
                                        @elseif($election->status === 'open')
                                            <form method="POST" action="{{ route('elections.close', $election) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        style="background:#dc2626; color:white; padding:5px 11px; border-radius:6px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                    Close
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button"
                                                onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='flex'"
                                                style="background:#f1f5f9; color:#64748b; padding:5px 11px; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer; font-size:12px; font-weight:600;">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- EDIT POPUP (motion) --}}
                            <div id="edit-group-election-box-{{ $election->id }}"
                                 style="display:none;"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
                                    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Motion</h2>
                                    <form method="POST" action="{{ route('elections.update', $election) }}">
                                        @csrf @method('PATCH')
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Motion Title</label>
                                            <input type="text" name="title" value="{{ $election->title }}" required
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Meeting</label>
                                            <select name="group_id" required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                @foreach($groups as $meeting)
                                                    <option value="{{ $meeting->id }}" @if($election->group_id === $meeting->id) selected @endif>{{ $meeting->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @php $motionItem = $election->votingItems->first(); @endphp
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Voting Visibility</label>
                                            <select name="voting_mode" required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                <option value="anonymous" @if(($motionItem?->voting_mode ?? 'anonymous') === 'anonymous') selected @endif>Anonymous voters</option>
                                                <option value="named" @if(($motionItem?->voting_mode ?? 'anonymous') === 'named') selected @endif>Visible voters</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="description" rows="3"
                                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ $election->description }}</textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="datetime-local" name="starts_at"
                                                   value="{{ $election->starts_at ? $election->starts_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                            <input type="datetime-local" name="ends_at"
                                                   value="{{ $election->ends_at ? $election->ends_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button type="button"
                                                    onclick="document.getElementById('edit-group-election-box-{{ $election->id }}').style.display='none'"
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">Cancel</button>
                                            <button type="submit"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- DELETE POPUP (motion) --}}
                            <div id="delete-group-election-box-{{ $election->id }}"
                                 style="display:none;"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                                    <h2 class="text-xl font-bold text-gray-800 mb-4">Delete Motion</h2>
                                    <p class="text-gray-600 text-sm mb-6">Permanently remove this motion, all voting records, and all options?</p>
                                    <div class="flex justify-end gap-3">
                                        <button type="button"
                                                onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='none'"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">Cancel</button>
                                        <form method="POST" action="{{ route('elections.destroy', $election) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">Delete Motion</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @elseif($election->isPositional())

                            {{-- POSITIONAL ELECTION --}}
                            <div style="padding:12px 20px; border-bottom:1px solid #f8fafc;">

                                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:{{ $election->votingItems->isNotEmpty() ? '10px' : '0' }};">
                                    <div style="flex:1; min-width:0;">
                                        <div style="display:flex; align-items:center; gap:7px; flex-wrap:wrap; margin-bottom:2px;">
                                            <span style="font-size:10px; font-weight:700; background:#eef2ff; color:#4f46e5; padding:2px 7px; border-radius:999px; text-transform:uppercase; letter-spacing:0.05em;">Election</span>
                                            <span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $election->title }}</span>
                                            @if($election->status === 'open')
                                                <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Open</span>
                                            @elseif($election->status === 'closed')
                                                <span style="background:#f1f5f9; color:#64748b; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Closed</span>
                                            @else
                                                <span style="background:#fef9c3; color:#854d0e; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;">Draft</span>
                                            @endif
                                        </div>
                                        @if($election->description)
                                            <p style="font-size:12px; color:#94a3b8; margin:0;">{{ $election->description }}</p>
                                        @endif
                                    </div>
                                    <button type="button"
                                            onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='flex'"
                                            style="background:#f1f5f9; color:#64748b; padding:5px 11px; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer; font-size:12px; font-weight:600; flex-shrink:0;">
                                        Delete
                                    </button>
                                </div>

                                {{-- Positions --}}
                                @if($election->votingItems->isNotEmpty())
                                    <div style="display:flex; flex-direction:column; gap:6px; padding-left:16px; border-left:2px solid #e0e7ff;">
                                        @foreach($election->votingItems as $position)
                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                                                <span style="font-size:13px; color:#374151; font-weight:500;">{{ $position->title }}</span>
                                                <div style="display:flex; align-items:center; gap:6px;">
                                                    @if($position->status === 'draft')
                                                        <form method="POST" action="{{ route('positions.open', $position) }}">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" style="background:#16a34a; color:white; padding:4px 10px; border-radius:5px; border:none; cursor:pointer; font-size:11px; font-weight:600;">Start</button>
                                                        </form>
                                                    @elseif($position->status === 'open')
                                                        <form method="POST" action="{{ route('positions.close', $position) }}">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" style="background:#dc2626; color:white; padding:4px 10px; border-radius:5px; border:none; cursor:pointer; font-size:11px; font-weight:600;">Close</button>
                                                        </form>
                                                    @endif
                                                    @if($position->status === 'open')
                                                        <span style="background:#dcfce7; color:#15803d; font-size:10px; font-weight:700; padding:2px 7px; border-radius:999px;">Open</span>
                                                    @elseif($position->status === 'closed')
                                                        <span style="background:#f1f5f9; color:#64748b; font-size:10px; font-weight:700; padding:2px 7px; border-radius:999px;">Closed</span>
                                                    @else
                                                        <span style="background:#fef9c3; color:#854d0e; font-size:10px; font-weight:700; padding:2px 7px; border-radius:999px;">Draft</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- DELETE POPUP (positional) --}}
                                <div id="delete-group-election-box-{{ $election->id }}"
                                     style="display:none;"
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                                        <h2 class="text-xl font-bold text-gray-800 mb-4">Delete Election</h2>
                                        <p class="text-gray-600 text-sm mb-6">Permanently remove this election, all voting records, and all options?</p>
                                        <div class="flex justify-end gap-3">
                                            <button type="button"
                                                    onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='none'"
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">Cancel</button>
                                            <form method="POST" action="{{ route('elections.destroy', $election) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">Delete Election</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        @endif

                    @empty
                        <p style="color:#94a3b8; font-size:13px; padding:16px 20px; margin:0;">No elections or motions created under this meeting yet.</p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleSection(bodyId, chevronId) {
            const body = document.getElementById(bodyId);
            const chevron = document.getElementById(chevronId);
            if (body.style.display === 'none') {
                body.style.display = 'block';
                chevron.style.transform = 'rotate(0deg)';
            } else {
                body.style.display = 'none';
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        function updateSelectedUsers{{ $group->id }}() {
            const checkedUsers = document.querySelectorAll('.meeting-user-checkbox-{{ $group->id }}:checked');
            const label = document.getElementById('selected-users-label-{{ $group->id }}');
            if (checkedUsers.length === 0) {
                label.innerText = 'Select users to add';
            } else if (checkedUsers.length === 1) {
                label.innerText = '1 user selected';
            } else {
                label.innerText = checkedUsers.length + ' users selected';
            }
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('users-dropdown-{{ $group->id }}');
            if (!dropdown) return;
            const button = dropdown.previousElementSibling;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
