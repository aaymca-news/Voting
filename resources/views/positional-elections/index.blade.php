<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Elections</h1>
                <a href="{{ route('positional-elections.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow">
                    Create Election
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="space-y-8">

                @forelse($elections->groupBy('group_id') as $groupElections)
                    @php $meeting = $groupElections->first()->group; @endphp

                    <div class="bg-white shadow rounded-2xl overflow-hidden">

                        {{-- Meeting header --}}
                        <div class="bg-gray-50 border-b px-8 py-6">
                            <h2 class="text-3xl font-bold text-gray-900">{{ $meeting?->name ?? 'No Meeting Assigned' }}</h2>
                            @if($meeting?->description)
                                <p class="text-gray-500 mt-1 text-lg">{{ $meeting->description }}</p>
                            @endif
                        </div>

                        <div class="p-6 space-y-6">

                            @foreach($groupElections as $election)

                                <div class="border rounded-xl p-6">

                                    {{-- ── Election header row ── --}}
                                    <div class="flex justify-between items-start gap-4 mb-6">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900">{{ $election->title }}</h3>
                                            @if($election->description)
                                                <p class="text-gray-500 mt-1">{{ $election->description }}</p>
                                            @endif
                                        </div>

                                        {{-- Election-level actions --}}
                                        <div class="flex flex-wrap gap-2 items-start">
                                            <a href="{{ route('votes.results', $election) }}"
                                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                                View All Results
                                            </a>

                                            <a href="{{ route('positions.create', $election) }}"
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                                + Add Position
                                            </a>

                                            <button type="button"
                                                    onclick="document.getElementById('edit-election-{{ $election->id }}').style.display='flex'"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                                Edit
                                            </button>

                                            <button type="button"
                                                    onclick="document.getElementById('delete-election-{{ $election->id }}').style.display='flex'"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                                Delete
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ── Positions list ── --}}
                                    @if($election->votingItems->isEmpty())
                                        <div class="bg-gray-50 rounded-xl p-6 text-center">
                                            <p class="text-gray-400 italic mb-3">No positions added yet.</p>
                                            <a href="{{ route('positions.create', $election) }}"
                                               class="text-blue-600 hover:underline text-sm font-medium">+ Add your first position</a>
                                        </div>
                                    @else
                                        <div class="space-y-5">
                                            @foreach($election->votingItems as $position)

                                                <div class="bg-gray-50 rounded-xl border p-5">

                                                    {{-- Position header --}}
                                                    <div class="flex flex-wrap justify-between items-start gap-3 mb-4">
                                                        <div>
                                                            <div class="flex items-center gap-3">
                                                                <h4 class="text-xl font-bold text-gray-800">{{ $position->title }}</h4>
                                                                @if($position->status === 'open')
                                                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                                                @elseif($position->status === 'closed')
                                                                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                                                @else
                                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Draft</span>
                                                                @endif
                                                            </div>
                                                            @if($position->description)
                                                                <p class="text-gray-500 text-sm mt-1">{{ $position->description }}</p>
                                                            @endif
                                                        </div>

                                                        {{-- Position-level controls --}}
                                                        <div class="flex flex-wrap gap-2">

                                                            {{-- Results --}}
                                                            <a href="{{ route('positions.results', $position) }}"
                                                               class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-3 py-2 rounded-lg">
                                                                Results
                                                            </a>

                                                            {{-- Start / Close voting --}}
                                                            @if($position->status === 'draft')
                                                                <form method="POST" action="{{ route('positions.open', $position) }}">
                                                                    @csrf @method('PATCH')
                                                                    <button class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg">
                                                                        Start Voting
                                                                    </button>
                                                                </form>
                                                            @elseif($position->status === 'open')
                                                                <form method="POST" action="{{ route('positions.close', $position) }}">
                                                                    @csrf @method('PATCH')
                                                                    <button class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-2 rounded-lg">
                                                                        Close Voting
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            {{-- Add Candidates (only in draft) --}}
                                                            @if($position->status === 'draft')
                                                                <a href="{{ route('candidates.create', $position) }}"
                                                                   class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-3 py-2 rounded-lg">
                                                                    Add Candidates
                                                                </a>
                                                            @endif

                                                            {{-- Delete Position --}}
                                                            <button type="button"
                                                                    onclick="document.getElementById('delete-position-{{ $position->id }}').style.display='flex'"
                                                                    style="background:#374151; color:white; font-size:13px; padding:8px 14px; border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Candidate cards --}}
                                                    @if($position->options->isEmpty())
                                                        <p class="text-gray-400 italic text-sm">No candidates yet.</p>
                                                    @else
                                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                                            @foreach($position->options as $candidate)
                                                                <div class="bg-white rounded-xl shadow-sm border p-3 flex flex-col items-center text-center">

                                                                    @if($candidate->photo_path)
                                                                        <img src="{{ asset('storage/' . $candidate->photo_path) }}"
                                                                             alt="{{ $candidate->name }}"
                                                                             class="w-16 h-16 rounded-full object-cover mb-2 border-2 border-blue-200">
                                                                    @else
                                                                        <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-2 border-2 border-blue-200">
                                                                            <span class="text-2xl">👤</span>
                                                                        </div>
                                                                    @endif

                                                                    <p class="font-bold text-gray-800 text-xs leading-tight">{{ $candidate->name }}</p>

                                                                    @if($candidate->description)
                                                                        <p class="text-gray-400 text-xs mt-1 line-clamp-2">{{ $candidate->description }}</p>
                                                                    @endif

                                                                    @if($position->status === 'draft')
                                                                    <button type="button"
                                                                            onclick="document.getElementById('delete-candidate-{{ $candidate->id }}').style.display='flex'"
                                                                            class="mt-2 text-red-500 hover:text-red-700 text-xs underline">
                                                                        Remove
                                                                    </button>
                                                                    @endif
                                                                </div>

                                                                @if($position->status === 'draft')
                                                                {{-- Delete candidate popup --}}
                                                                <div id="delete-candidate-{{ $candidate->id }}" style="display:none;"
                                                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                                                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
                                                                        <h2 class="text-lg font-bold text-gray-800 mb-3">Remove Candidate</h2>
                                                                        <p class="text-gray-600 mb-5">Remove <strong>{{ $candidate->name }}</strong>?</p>
                                                                        <div class="flex justify-end gap-3">
                                                                            <button type="button"
                                                                                    onclick="document.getElementById('delete-candidate-{{ $candidate->id }}').style.display='none'"
                                                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</button>
                                                                            <form method="POST" action="{{ route('candidates.destroy', $candidate) }}">
                                                                                @csrf @method('DELETE')
                                                                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Remove</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                </div>

                                                {{-- Delete position popup --}}
                                                <div id="delete-position-{{ $position->id }}" style="display:none;"
                                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
                                                        <h2 class="text-lg font-bold text-gray-800 mb-3">Delete Position</h2>
                                                        <p class="text-gray-600 mb-5">Delete <strong>{{ $position->title }}</strong> and all its candidates?</p>
                                                        <div class="flex justify-end gap-3">
                                                            <button type="button"
                                                                    onclick="document.getElementById('delete-position-{{ $position->id }}').style.display='none'"
                                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</button>
                                                            <form method="POST" action="{{ route('positions.destroy', $position) }}">
                                                                @csrf @method('DELETE')
                                                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                {{-- Edit election popup --}}
                                <div id="edit-election-{{ $election->id }}" style="display:none;"
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Election</h2>
                                        <form method="POST" action="{{ route('positional-elections.update', $election) }}">
                                            @csrf @method('PATCH')
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                                <input type="text" name="title" value="{{ $election->title }}" required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting</label>
                                                <select name="group_id" required
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                    @foreach($groups as $g)
                                                        <option value="{{ $g->id }}" @selected($election->group_id === $g->id)>{{ $g->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                <textarea name="description" rows="3"
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $election->description }}</textarea>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                                <input type="datetime-local" name="starts_at"
                                                    value="{{ $election->starts_at?->format('Y-m-d\TH:i') }}"
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                            </div>
                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                                <input type="datetime-local" name="ends_at"
                                                    value="{{ $election->ends_at?->format('Y-m-d\TH:i') }}"
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                            </div>
                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                        onclick="document.getElementById('edit-election-{{ $election->id }}').style.display='none'"
                                                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</button>
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Delete election popup --}}
                                <div id="delete-election-{{ $election->id }}" style="display:none;"
                                     class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
                                        <h2 class="text-xl font-bold text-gray-800 mb-3">Delete Election</h2>
                                        <p class="text-gray-600 mb-2">This will permanently delete:</p>
                                        <ul class="list-disc ml-5 text-gray-600 mb-5 space-y-1 text-sm">
                                            <li>The election</li>
                                            <li>All positions and candidates</li>
                                            <li>All candidate photos</li>
                                            <li>All voting records</li>
                                        </ul>
                                        <div class="flex justify-end gap-3">
                                            <button type="button"
                                                    onclick="document.getElementById('delete-election-{{ $election->id }}').style.display='none'"
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Cancel</button>
                                            <form method="POST" action="{{ route('positional-elections.destroy', $election) }}">
                                                @csrf @method('DELETE')
                                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Delete Election</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>

                @empty
                    <div class="bg-white shadow rounded-2xl p-8 text-center">
                        <p class="text-gray-400 text-lg mb-4">No elections created yet.</p>
                        <a href="{{ route('positional-elections.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold">
                            Create Your First Election
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
