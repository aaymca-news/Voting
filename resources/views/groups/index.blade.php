<x-app-layout>
    <div style="background:#f1f5f9; min-height:100vh; padding:36px 0 56px;">
        <div style="max-width:1100px; margin:0 auto; padding:0 24px;">

            {{-- HEADER --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:32px;">
                <div>
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 5px;">Admin</p>
                    <h1 style="font-size:24px; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">Meetings</h1>
                </div>
                <a href="{{ route('groups.create') }}"
                   style="background:#2563eb; color:white; padding:9px 20px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">
                    + Create Meeting
                </a>
            </div>

            {{-- FLASH --}}
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

            {{-- LIST --}}
            <div style="background:white; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">

                @forelse($groups as $group)

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:16px 24px; gap:16px; flex-wrap:wrap; border-bottom:1px solid #f1f5f9;">

                        {{-- LEFT --}}
                        <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                            <div style="width:40px; height:40px; border-radius:10px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">🤝</div>
                            <div style="min-width:0;">
                                <a href="{{ route('groups.show', $group) }}"
                                   style="font-size:15px; font-weight:700; color:#1d4ed8; text-decoration:none;">
                                    {{ $group->name }}
                                </a>
                                @if($group->description)
                                    <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $group->description }}</p>
                                @endif
                                <p style="font-size:11px; color:#cbd5e1; margin:1px 0 0;">Code: {{ $group->code }}</p>
                            </div>
                        </div>

                        {{-- RIGHT --}}
                        <div style="display:flex; gap:6px; flex-shrink:0;">
                            <a href="{{ route('groups.show', $group) }}"
                               style="background:#2563eb; color:white; padding:6px 13px; border-radius:7px; text-decoration:none; font-size:12px; font-weight:600;">
                                Open
                            </a>
                            <button type="button"
                                    onclick="document.getElementById('edit-group-box-{{ $group->id }}').style.display='flex'"
                                    style="background:#f59e0b; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                Edit
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('delete-group-box-{{ $group->id }}').style.display='flex'"
                                    style="background:#dc2626; color:white; padding:6px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600;">
                                Delete
                            </button>
                        </div>

                    </div>

                    {{-- EDIT POPUP --}}
                    <div id="edit-group-box-{{ $group->id }}"
                         style="display:none;"
                         class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Meeting</h2>
                            <form method="POST" action="{{ route('groups.update', $group) }}">
                                @csrf @method('PATCH')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Name</label>
                                    <input type="text" name="name" value="{{ $group->name }}" required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="description" rows="3"
                                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $group->description }}</textarea>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button"
                                            onclick="document.getElementById('edit-group-box-{{ $group->id }}').style.display='none'"
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
                    <div id="delete-group-box-{{ $group->id }}"
                         style="display:none;"
                         class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Delete Meeting</h2>
                            <p class="text-gray-600 text-sm mb-3">Are you sure? This will permanently remove:</p>
                            <ul class="list-disc ml-5 text-gray-500 text-sm mb-6 space-y-1">
                                <li>The meeting</li>
                                <li>All meeting memberships</li>
                                <li>All elections and motions</li>
                                <li>All vote records</li>
                            </ul>
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                        onclick="document.getElementById('delete-group-box-{{ $group->id }}').style.display='none'"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                    Cancel
                                </button>
                                <form method="POST" action="{{ route('groups.destroy', $group) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Delete Meeting
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty

                    <div style="padding:48px; text-align:center;">
                        <p style="font-size:36px; margin:0 0 12px;">🤝</p>
                        <p style="color:#94a3b8; font-size:15px; margin:0 0 18px;">No meetings created yet.</p>
                        <a href="{{ route('groups.create') }}"
                           style="background:#2563eb; color:white; padding:9px 22px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">
                            Create First Meeting
                        </a>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
