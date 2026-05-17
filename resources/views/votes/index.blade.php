<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Votes Dashboard
                </h1>

                <p class="text-gray-500 mt-2">
                    Monitor voting activity, motion status, and results.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <a href="{{ route('elections.index') }}"
                   class="bg-white shadow rounded-2xl p-6 block hover:bg-gray-50"
                   style="text-decoration:none; color:inherit;">
                    <h2 class="text-lg font-semibold text-gray-600">
                        Meetings / Elections
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalElections }}
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        View all meetings/elections.
                    </p>
                </a>

                <a href="#motions"
                   class="bg-white shadow rounded-2xl p-6 block hover:bg-gray-50"
                   style="text-decoration:none; color:inherit;">
                    <h2 class="text-lg font-semibold text-gray-600">
                        Motions / Agendas
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalVotingItems }}
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        View all motions/agendas.
                    </p>
                </a>

                <a href="#votes-cast"
                   class="bg-white shadow rounded-2xl p-6 block hover:bg-gray-50"
                   style="text-decoration:none; color:inherit;">
                    <h2 class="text-lg font-semibold text-gray-600">
                        Votes Cast
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalVotes }}
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        View voting results.
                    </p>
                </a>

            </div>

            <div id="motions" class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-2xl font-bold mb-6">
                    Motion / Agenda Voting Status
                </h2>

                @forelse($elections as $election)

                    <div class="border rounded-xl p-5 mb-6">

                        <h3 class="text-xl font-bold">
                            {{ $election->title }}
                        </h3>

                        <p class="text-gray-500 mt-1 mb-4">
                            {{ $election->description }}
                        </p>

                        @forelse($election->votingItems as $item)

                            <div class="border rounded-lg p-4 mb-3">

                                <div class="flex justify-between items-start gap-4">

                                    <div>
                                        <h4 class="font-bold">
                                            {{ $item->title }}
                                        </h4>

                                        <p class="text-gray-500 text-sm mt-1">
                                            {{ $item->description }}
                                        </p>
                                    </div>

                                    @if($item->status === 'open')
                                        <span style="background:#16a34a; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                            Voting Open
                                        </span>
                                    @elseif($item->status === 'closed')
                                        <span style="background:#dc2626; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                            Closed
                                        </span>
                                    @else
                                        <span style="background:#6b7280; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                            Draft
                                        </span>
                                    @endif

                                </div>

                                <div id="votes-cast" style="margin-top:12px;">
                                    <a href="{{ route('votes.results', $election) }}"
                                       style="display:inline-block; background:#9333ea; color:white; padding:8px 14px; border-radius:8px; text-decoration:none;">
                                        View Results
                                    </a>
                                </div>

                            </div>

                        @empty

                            <p class="text-gray-500">
                                No motions/agendas added yet.
                            </p>

                        @endforelse

                    </div>

                @empty

                    <p class="text-gray-500">
                        No voting sessions available yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>