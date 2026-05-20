<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Motions
                </h1>

                <a href="{{ route('elections.create') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow">
                    Create Motion
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-8">

                @forelse($elections->groupBy('group_id') as $groupElections)

                    @php
                        $meeting = $groupElections->first()->group;
                    @endphp

                    <div class="bg-white shadow rounded-2xl overflow-hidden">

                        <div class="bg-gray-50 border-b px-8 py-6">

                            <h2 class="text-3xl font-bold text-gray-900">
                                {{ $meeting?->name ?? 'No Meeting Assigned' }}
                            </h2>

                            @if($meeting?->description)
                                <p class="text-gray-500 mt-2 text-lg">
                                    {{ $meeting->description }}
                                </p>
                            @endif

                            @if($meeting?->code)
                                <p class="text-gray-400 text-sm mt-2">
                                    Code: {{ $meeting->code }}
                                </p>
                            @endif

                        </div>

                        <div class="p-6">

                            @foreach($groupElections as $election)

                                <div class="border rounded-xl p-6 mb-6 last:mb-0">

                                    <div class="flex justify-between items-start gap-6">

                                        {{-- LEFT --}}
                                        <div>

                                            <h3 class="text-3xl font-bold text-gray-900">
                                                {{ $election->title }}
                                            </h3>

                                            <p class="text-gray-500 text-lg mt-2">
                                                {{ $election->description }}
                                            </p>

                                            <div class="mt-6 flex flex-wrap gap-3">

                                                <a href="{{ route('votes.results', $election) }}"
                                                   class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl text-lg">
                                                    View Results
                                                </a>

                                                @if($election->status === 'open')

                                                    <form method="POST" action="{{ route('elections.close', $election) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-lg">
                                                            Close Voting
                                                        </button>
                                                    </form>

                                                @elseif($election->status === 'draft')

                                                    <form method="POST" action="{{ route('elections.open', $election) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-lg">
                                                            Start Voting
                                                        </button>
                                                    </form>

                                                @endif

                                            </div>

                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="flex items-center gap-3">

                                            @if($election->status === 'draft')

                                                <button type="button"
                                                        onclick="document.getElementById('edit-election-box-{{ $election->id }}').style.display='flex'"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-xl text-lg">
                                                    Edit
                                                </button>

                                            @elseif($election->status === 'open')

                                                <span class="bg-green-600 text-white px-5 py-3 rounded-xl text-lg">
                                                    Open
                                                </span>

                                            @else

                                                <span class="bg-gray-600 text-white px-5 py-3 rounded-xl text-lg">
                                                    Closed
                                                </span>

                                            @endif

                                            <button type="button"
                                                    onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='flex'"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-lg">
                                                Delete
                                            </button>

                                        </div>

                                    </div>

                                    {{-- EDIT POPUP --}}
                                    <div id="edit-election-box-{{ $election->id }}"
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
                                                        @foreach($groups as $meetingOption)
                                                            <option value="{{ $meetingOption->id }}" @if($election->group_id === $meetingOption->id) selected @endif>
                                                                {{ $meetingOption->name }}
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
                                                            onclick="document.getElementById('edit-election-box-{{ $election->id }}').style.display='none'"
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

                                    {{-- DELETE POPUP --}}
                                    <div id="delete-election-box-{{ $election->id }}"
                                         style="display:none;"
                                         class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                                        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                                Delete Motion
                                            </h2>

                                            <p class="text-gray-600 mb-6">
                                                Are you sure you want to delete this Motion?
                                                This action will permanently remove:
                                            </p>

                                            <ul class="list-disc ml-5 text-gray-600 mb-6 space-y-1">
                                                <li>The motion</li>
                                                <li>All voting records</li>
                                                <li>All voting options</li>
                                            </ul>

                                            <div class="flex justify-end gap-3">

                                                <button type="button"
                                                        onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='none'"
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

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="bg-white shadow rounded-2xl p-6">
                        <p class="text-gray-500 text-lg">
                            No motions created yet.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>