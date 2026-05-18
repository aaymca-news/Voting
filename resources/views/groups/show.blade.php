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
                        Group Members
                    </h2>

                    <form method="POST"
                          action="{{ route('group-members.store', $group) }}"
                          class="flex gap-3 items-center flex-wrap">

                        @csrf

                        <select name="user_id"
                                class="border rounded-lg px-4 py-2"
                                required>

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach

                        </select>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Add User
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
                                    Confirm Remove From Group
                                </h3>

                                <p class="text-red-600 mb-4">
                                    Are you sure you want to remove this user from this group?
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
                            No users added to this group yet.
                        </p>

                    @endforelse

                </div>

            </div>

            {{-- ELECTIONS --}}
            <div class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-2xl font-bold mb-6">
                    Elections / Meetings under this Group
                </h2>

                @forelse($group->elections as $election)

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

                @empty

                    <p class="text-gray-500">
                        No elections/meetings created under this group yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>