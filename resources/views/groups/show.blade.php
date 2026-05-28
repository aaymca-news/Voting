<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $group->name }}
                </h1>

                <p class="text-gray-500 mt-2">
                    {{ $group->description }}
                </p>

                <p class="text-gray-400 text-sm mt-2">
                    Code: {{ $group->code }}
                </p>
            </div>

            {{-- GROUP MEMBERS --}}
            <div class="bg-white shadow rounded-2xl p-6 mb-8">

                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">

                    <h2 class="text-2xl font-bold">
                        Meeting Members
                    </h2>

                    <form method="POST"
                          action="{{ route('group-members.store', $group) }}"
                          class="flex gap-3 items-start flex-wrap">

                        @csrf

                        @php
                            $memberIds = $group->users->pluck('id')->toArray();
                        @endphp

                        <div style="position:relative; min-width:420px;">

                            <button type="button"
                                    onclick="document.getElementById('users-dropdown-{{ $group->id }}').style.display = document.getElementById('users-dropdown-{{ $group->id }}').style.display === 'block' ? 'none' : 'block'"
                                    style="width:100%; border:1px solid #6b7280; border-radius:8px; padding:10px 16px; background:white; text-align:left; display:flex; justify-content:space-between; align-items:center;">

                                <span id="selected-users-label-{{ $group->id }}">
                                    Select Users
                                </span>

                                <span>
                                    ˅
                                </span>

                            </button>

                            <div id="users-dropdown-{{ $group->id }}"
                                 style="display:none; position:absolute; top:52px; left:0; right:0; background:white; border:1px solid #d1d5db; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,0.12); padding:10px; z-index:40; max-height:260px; overflow-y:auto;">

                                @foreach($users as $user)

                                    @php
                                        $alreadyAdded = in_array($user->id, $memberIds);
                                    @endphp

                                    <label style="display:flex; align-items:center; gap:10px; padding:10px; border-radius:8px; cursor:{{ $alreadyAdded ? 'not-allowed' : 'pointer' }}; color:{{ $alreadyAdded ? '#9ca3af' : '#111827' }}; background:{{ $alreadyAdded ? '#f3f4f6' : 'white' }};">

                                        <input type="checkbox"
                                               name="user_ids[]"
                                               value="{{ $user->id }}"
                                               class="meeting-user-checkbox-{{ $group->id }}"
                                               onchange="updateSelectedUsers{{ $group->id }}()"
                                               @if($alreadyAdded) disabled @endif>

                                        <span>
                                            {{ $user->name }} ({{ $user->email }})

                                            @if($alreadyAdded)
                                                - Already added
                                            @endif
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Add Users
                        </button>

                    </form>

                </div>

                <div class="space-y-3">

                    @forelse($group->users as $user)

                        <div class="border rounded-lg px-4 py-4">

                            <div class="flex justify-between items-center">

                                <div>
                                    <p class="font-semibold text-lg">
                                        {{ $user->name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">

                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs">
                                        Voter
                                    </span>

                                    <button type="button"
                                            onclick="document.getElementById('remove-member-box-{{ $user->id }}').style.display='block'"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Remove
                                    </button>

                                </div>

                            </div>

                            {{-- REMOVE MEMBER CONFIRMATION --}}
                            <div id="remove-member-box-{{ $user->id }}"
                                 style="display:none;"
                                 class="mt-4 bg-red-50 border border-red-200 rounded-xl p-5">

                                <h3 class="text-lg font-bold text-red-700 mb-2">
                                    Confirm Remove From Meeting
                                </h3>

                                <p class="text-red-600 mb-4">
                                    Are you sure you want to remove this user from this meeting?
                                </p>

                                <div class="flex gap-3">

                                    <form method="POST"
                                          action="{{ route('group-members.destroy', [$group, $user]) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                            Yes, Remove
                                        </button>

                                    </form>

                                    <button type="button"
                                            onclick="document.getElementById('remove-member-box-{{ $user->id }}').style.display='none'"
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                        Cancel
                                    </button>

                                </div>

                            </div>

                        </div>

                    @empty

                        <p class="text-gray-500">
                            No users added to this meeting yet.
                        </p>

                    @endforelse

                </div>

            </div>

            {{-- ELECTIONS --}}
            <div class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-2xl font-bold mb-6">
                    Elections / Motions under this Group
                </h2>

                @forelse($group->elections as $election)

                    @if($election->isMotion())

                        {{-- ── MOTION ── --}}
                        <div class="border rounded-xl p-6 mb-8">

                            <div class="flex justify-between items-start gap-4">

                                <div>
                                    <h3 class="text-2xl font-bold">
                                        {{ $election->title }}
                                    </h3>

                                    <p class="text-gray-500 mt-1">
                                        {{ $election->description }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3 flex-wrap justify-end">

                                    @if($election->status === 'draft')

                                        <button type="button"
                                                onclick="document.getElementById('edit-group-election-box-{{ $election->id }}').style.display='flex'"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('elections.open', $election) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                                Start Voting
                                            </button>
                                        </form>

                                    @elseif($election->status === 'open')

                                        <form method="POST" action="{{ route('elections.close', $election) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                                Close Voting
                                            </button>
                                        </form>

                                    @endif

                                    <button type="button"
                                            onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='flex'"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                        Delete
                                    </button>

                                    @if($election->status === 'open')

                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                            Open
                                        </span>

                                    @elseif($election->status === 'closed')

                                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                                            Closed
                                        </span>

                                    @else

                                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm">
                                            Draft
                                        </span>

                                    @endif

                                </div>

                            </div>

                            {{-- EDIT POPUP (motion) --}}
                            <div id="edit-group-election-box-{{ $election->id }}"
                                 style="display:none;"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">

                                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                        Edit Motion
                                    </h2>

                                    <form method="POST"
                                          action="{{ route('elections.update', $election) }}">

                                        @csrf
                                        @method('PATCH')

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Motion Title
                                            </label>

                                            <input type="text"
                                                   name="title"
                                                   value="{{ $election->title }}"
                                                   required
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Meeting
                                            </label>

                                            <select name="group_id"
                                                    required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                @foreach($groups as $meeting)
                                                    <option value="{{ $meeting->id }}" @if($election->group_id === $meeting->id) selected @endif>
                                                        {{ $meeting->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @php
                                            $motionItem = $election->votingItems->first();
                                        @endphp

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Voting Visibility
                                            </label>

                                            <select name="voting_mode"
                                                    required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                <option value="anonymous" @if(($motionItem?->voting_mode ?? 'anonymous') === 'anonymous') selected @endif>
                                                    Anonymous voters
                                                </option>

                                                <option value="named" @if(($motionItem?->voting_mode ?? 'anonymous') === 'named') selected @endif>
                                                    Visible voters
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Description
                                            </label>

                                            <textarea name="description"
                                                      rows="3"
                                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ $election->description }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Start Date
                                            </label>

                                            <input type="datetime-local"
                                                   name="starts_at"
                                                   value="{{ $election->starts_at ? $election->starts_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                End Date
                                            </label>

                                            <input type="datetime-local"
                                                   name="ends_at"
                                                   value="{{ $election->ends_at ? $election->ends_at->format('Y-m-d\TH:i') : '' }}"
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        </div>

                                        <div class="flex justify-end gap-3">

                                            <button type="button"
                                                    onclick="document.getElementById('edit-group-election-box-{{ $election->id }}').style.display='none'"
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                                Cancel
                                            </button>

                                            <button type="submit"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                                                Save Changes
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                            {{-- DELETE POPUP (motion) --}}
                            <div id="delete-group-election-box-{{ $election->id }}"
                                 style="display:none;"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                        Delete Motion
                                    </h2>

                                    <p class="text-gray-600 mb-6">
                                        Are you sure you want to delete this motion?
                                        This action will permanently remove all voting records and voting options.
                                    </p>

                                    <div class="flex justify-end gap-3">

                                        <button type="button"
                                                onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='none'"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                            Cancel
                                        </button>

                                        <form method="POST"
                                              action="{{ route('elections.destroy', $election) }}">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                                Delete Motion
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @elseif($election->isPositional())

                        {{-- ── POSITIONAL ELECTION ── --}}
                        <div class="border rounded-xl p-6 mb-8">

                            <div class="flex justify-between items-start gap-4 mb-4">

                                <div>
                                    <h3 class="text-2xl font-bold">
                                        {{ $election->title }}
                                    </h3>

                                    @if($election->description)
                                        <p class="text-gray-500 mt-1">
                                            {{ $election->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 flex-wrap justify-end">

                                    <button type="button"
                                            onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='flex'"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                        Delete
                                    </button>

                                    @if($election->status === 'open')

                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                            Open
                                        </span>

                                    @elseif($election->status === 'closed')

                                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                                            Closed
                                        </span>

                                    @else

                                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm">
                                            Draft
                                        </span>

                                    @endif

                                </div>

                            </div>

                            {{-- POSITIONS LIST --}}
                            @if($election->votingItems->isEmpty())

                                <p class="text-gray-500 text-sm">
                                    No positions added yet.
                                </p>

                            @else

                                <div class="space-y-3 mt-2">

                                    @foreach($election->votingItems as $position)

                                        <div class="border border-gray-200 rounded-lg px-4 py-3 flex justify-between items-center gap-4 flex-wrap">

                                            <span class="font-medium text-gray-800">
                                                {{ $position->title }}
                                            </span>

                                            <div class="flex items-center gap-3 flex-wrap">

                                                @if($position->status === 'draft')

                                                    <form method="POST" action="{{ route('positions.open', $position) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm">
                                                            Start Voting
                                                        </button>
                                                    </form>

                                                @elseif($position->status === 'open')

                                                    <form method="POST" action="{{ route('positions.close', $position) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm">
                                                            Close Voting
                                                        </button>
                                                    </form>

                                                @endif

                                                @if($position->status === 'open')

                                                    <span class="bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                        Open
                                                    </span>

                                                @elseif($position->status === 'closed')

                                                    <span class="bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                        Closed
                                                    </span>

                                                @else

                                                    <span class="bg-yellow-100 text-yellow-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                        Draft
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                            {{-- DELETE POPUP (positional election) --}}
                            <div id="delete-group-election-box-{{ $election->id }}"
                                 style="display:none;"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                        Delete Election
                                    </h2>

                                    <p class="text-gray-600 mb-6">
                                        Are you sure you want to delete this election?
                                        This action will permanently remove all voting records and voting options.
                                    </p>

                                    <div class="flex justify-end gap-3">

                                        <button type="button"
                                                onclick="document.getElementById('delete-group-election-box-{{ $election->id }}').style.display='none'"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                            Cancel
                                        </button>

                                        <form method="POST"
                                              action="{{ route('elections.destroy', $election) }}">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                                Delete Election
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                @empty

                    <p class="text-gray-500">
                        No elections/motions created under this meeting yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>

    <script>
        function updateSelectedUsers{{ $group->id }}() {
            const checkedUsers = document.querySelectorAll('.meeting-user-checkbox-{{ $group->id }}:checked');
            const label = document.getElementById('selected-users-label-{{ $group->id }}');

            if (checkedUsers.length === 0) {
                label.innerText = 'Select Users';
            } else if (checkedUsers.length === 1) {
                label.innerText = '1 user selected';
            } else {
                label.innerText = checkedUsers.length + ' users selected';
            }
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('users-dropdown-{{ $group->id }}');

            if (!dropdown) {
                return;
            }

            const button = dropdown.previousElementSibling;

            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</x-app-layout>