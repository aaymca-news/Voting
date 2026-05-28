<x-app-layout>
    <div style="background:#f1f5f9; min-height:100vh; padding:36px 0 56px;">
        <div style="max-width:1100px; margin:0 auto; padding:0 24px;">

            {{-- HEADER --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:32px;">
                <div>
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 5px;">Admin</p>
                    <h1 style="font-size:24px; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">Motions</h1>
                </div>
                <a href="{{ route('elections.create') }}"
                   style="background:#16a34a; color:white; padding:9px 20px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">
                    + Create Motion
                </a>
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <div style="background:#f0fdf4; border:1px solid #86efac; color:#166534; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-size:14px; font-weight:600;">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-size:14px; font-weight:600;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ELECTION GROUPS --}}
            <div style="display:flex; flex-direction:column; gap:20px;">

                @forelse($elections->groupBy('group_id') as $groupElections)

                    @php $meeting = $groupElections->first()->group; @endphp

                    <div style="background:white; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">

                        {{-- Group header --}}
                        <div style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:16px 24px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:36px; height:36px; border-radius:9px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">🤝</div>
                                <div>
                                    <h2 style="font-size:15px; font-weight:700; color:#0f172a; margin:0;">
                                        {{ $meeting?->name ?? 'No Meeting Assigned' }}
                                    </h2>
                                    @if($meeting?->code)
                                        <p style="font-size:11px; color:#94a3b8; margin:1px 0 0;">Code: {{ $meeting->code }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Motions list --}}
                        <div style="padding:8px 0;">

                            @foreach($groupElections as $election)

                                <div style="display:flex; justify-content:space-between; align-items:center; padding:14px 24px; gap:16px; flex-wrap:wrap; border-bottom:1px solid #f1f5f9;">

                                    {{-- LEFT: title + description + status + action buttons --}}
                                    <div style="flex:1; min-width:0;">

                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:3px;">
                                            <span style="font-size:15px; font-weight:700; color:#0f172a;">{{ $election->title }}</span>

                                            @if($election->status === 'open')
                                                <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:2px 9px; border-radius:999px;">Open</span>
                                            @elseif($election->status === 'closed')
                                                <span style="background:#f1f5f9; color:#64748b; font-size:11px; font-weight:700; padding:2px 9px; border-radius:999px;">Closed</span>
                                            @else
                                                <span style="background:#fef9c3; color:#854d0e; font-size:11px; font-weight:700; padding:2px 9px; border-radius:999px;">Draft</span>
                                            @endif
                                        </div>

                                        @if($election->description)
                                            <p style="font-size:13px; color:#94a3b8; margin:0 0 10px;">{{ $election->description }}</p>
                                        @else
                                            <div style="margin-bottom:10px;"></div>
                                        @endif

                                        <div style="display:flex; gap:6px; flex-wrap:wrap;">

                                            <a href="{{ route('votes.results', $election) }}"
                                               style="background:#7c3aed; color:white; padding:6px 13px; border-radius:7px; text-decoration:none; font-size:12px; font-weight:600;">
                                                View Results
                                            </a>

                                            @if($election->status === 'open')
                                                <form method="POST" action="{{ route('elections.close', $election) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            style="background:#dc2626; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                        Close Voting
                                                    </button>
                                                </form>
                                            @elseif($election->status === 'draft')
                                                <form method="POST" action="{{ route('elections.open', $election) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            style="background:#16a34a; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                        Start Voting
                                                    </button>
                                                </form>
                                            @endif

                                        </div>

                                    </div>

                                    {{-- RIGHT: Edit + Delete --}}
                                    <div style="display:flex; gap:6px; flex-shrink:0;">

                                        @if($election->status === 'draft')
                                            <button type="button"
                                                    onclick="document.getElementById('edit-election-box-{{ $election->id }}').style.display='flex'"
                                                    style="background:#f59e0b; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                                Edit
                                            </button>
                                        @endif

                                        <button type="button"
                                                onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='flex'"
                                                style="background:#dc2626; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                            Delete
                                        </button>

                                    </div>

                                </div>

                                {{-- EDIT POPUP --}}
                                <div id="edit-election-box-{{ $election->id }}"
                                     style="display:none;"
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Motion</h2>
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
                                                    @foreach($groups as $meetingOption)
                                                        <option value="{{ $meetingOption->id }}" @if($election->group_id === $meetingOption->id) selected @endif>
                                                            {{ $meetingOption->name }}
                                                        </option>
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
                                                        onclick="document.getElementById('edit-election-box-{{ $election->id }}').style.display='none'"
                                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- DELETE POPUP --}}
                                <div id="delete-election-box-{{ $election->id }}"
                                     style="display:none;"
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                                        <h2 class="text-xl font-bold text-gray-800 mb-4">Delete Motion</h2>
                                        <p class="text-gray-600 mb-3 text-sm">Are you sure you want to delete this motion? This will permanently remove:</p>
                                        <ul class="list-disc ml-5 text-gray-500 text-sm mb-6 space-y-1">
                                            <li>The motion</li>
                                            <li>All voting records</li>
                                            <li>All voting options</li>
                                        </ul>
                                        <div class="flex justify-end gap-3">
                                            <button type="button"
                                                    onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='none'"
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('elections.destroy', $election) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                                    Delete Motion
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div style="background:white; border:1px solid #e2e8f0; border-radius:16px; padding:48px; text-align:center;">
                        <p style="font-size:36px; margin:0 0 12px;">✋</p>
                        <p style="color:#94a3b8; font-size:15px; margin:0 0 18px;">No motions created yet.</p>
                        <a href="{{ route('elections.create') }}"
                           style="background:#16a34a; color:white; padding:9px 22px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">
                            Create First Motion
                        </a>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
