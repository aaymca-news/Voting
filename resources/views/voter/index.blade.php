<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Voting Portal
                </h1>

                <p class="text-gray-500 mt-2">
                    View your assigned meetings and vote on open motions.
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 rounded-lg px-4 py-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 rounded-lg px-4 py-3 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @forelse($groups as $group)

                <div class="bg-white shadow rounded-2xl p-6 mb-6">

                    <h2 class="text-2xl font-bold">
                        {{ $group->name }}
                    </h2>

                    <p class="text-gray-500 mt-1">
                        {{ $group->description }}
                    </p>

                    <p class="text-gray-400 text-sm mt-2 mb-6">
                        Code: {{ $group->code }}
                    </p>

                    @forelse($group->elections as $election)

                        @php
                            $motion = $election->votingItems->first();
                        @endphp

                        @if($motion)

                            <div class="border rounded-xl p-5 mb-5">

                                <h3 class="text-xl font-bold">
                                    {{ $election->title }}
                                </h3>

                                <p class="text-gray-500 mt-1 mb-4">
                                    {{ $election->description }}
                                </p>

                                @php
                                    $userVote = \App\Models\Vote::with('electionOption')
                                        ->where('user_id', auth()->id())
                                        ->where('voting_item_id', $motion->id)
                                        ->first();
                                @endphp

                                @if($userVote)

                                    <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px;">
                                        You voted:
                                        <strong>
                                            {{ $userVote->electionOption->name }}
                                        </strong>
                                    </div>

                                @else

                                    <form method="POST" action="{{ route('votes.store', $election) }}">
                                        @csrf

                                        <input type="hidden"
                                               name="voting_item_id"
                                               value="{{ $motion->id }}">

                                        <div class="space-y-3">

                                            @foreach($motion->options as $option)

                                                <label class="flex items-center gap-3 border rounded-lg px-4 py-3 cursor-pointer hover:bg-gray-50">

                                                    <input type="radio"
                                                           name="election_option_id"
                                                           value="{{ $option->id }}"
                                                           required>

                                                    <span class="font-medium">
                                                        {{ $option->name }}
                                                    </span>

                                                </label>

                                            @endforeach

                                        </div>

                                        <div class="mt-4">

                                            <button type="button"
                                                    onclick="document.getElementById('confirm-box-{{ $motion->id }}').style.display='block'"
                                                    style="background:#2563eb; color:white; padding:10px 16px; border:none; border-radius:8px;">
                                                Submit Vote
                                            </button>

                                        </div>

                                        <div id="confirm-box-{{ $motion->id }}"
                                             style="display:none; margin-top:16px; background:#fff7ed; border:1px solid #fdba74; color:#9a3412; padding:16px; border-radius:10px;">

                                            <p class="font-semibold mb-2">
                                                Confirm Your Vote
                                            </p>

                                            <p class="mb-4">
                                                Are you sure you want to submit this vote? Once submitted, you cannot vote again on this motion.
                                            </p>

                                            <div style="display:flex; gap:10px;">

                                                <button type="submit"
                                                        style="background:#16a34a; color:white; padding:8px 14px; border:none; border-radius:8px;">
                                                    Yes, Submit Vote
                                                </button>

                                                <button type="button"
                                                        onclick="document.getElementById('confirm-box-{{ $motion->id }}').style.display='none'"
                                                        style="background:#6b7280; color:white; padding:8px 14px; border:none; border-radius:8px;">
                                                    Cancel
                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                @endif

                            </div>

                        @endif

                    @empty

                        <p class="text-gray-500">
                            No motions are currently open for voting in this meeting.
                        </p>

                    @endforelse

                </div>

            @empty

                <div class="bg-white shadow rounded-2xl p-6">
                    <p class="text-gray-500">
                        You have not been added to any meeting yet.
                    </p>
                </div>

            @endforelse

        </div>
    </div>
</x-app-layout>