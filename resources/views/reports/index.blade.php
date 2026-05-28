<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Reports
                </h1>

                <p class="text-gray-500 mt-2">
                    View meeting summaries and download full PDF reports for all motions and positional elections.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @forelse($groups as $group)

                    @php
                        $motionsCount = $group->elections
                            ->where('election_type', 'motion')
                            ->count();

                        $positionsCount = $group->elections
                            ->where('election_type', 'positional')
                            ->sum(fn($e) => $e->votingItems->count());

                        $totalVotes = $group->elections->sum(function ($election) {
                            return $election->votingItems->sum(function ($item) {
                                return $item->options->sum(function ($option) {
                                    return $option->votes->count();
                                });
                            });
                        });

                        $ongoingCount = $group->elections->where('status', 'open')->count();
                        $closedCount  = $group->elections->where('status', 'closed')->count();
                    @endphp

                    <div class="bg-white shadow rounded-2xl p-6 flex flex-col justify-between">

                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">
                                {{ $group->name }}
                            </h2>

                            <p class="text-gray-500 mt-2">
                                {{ $group->description }}
                            </p>

                            <p class="text-gray-400 text-sm mt-1">
                                Code: {{ $group->code }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mt-6">

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Total Motions</p>
                                    <p class="text-3xl font-bold mt-1 text-gray-800">{{ $motionsCount }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Total Positions</p>
                                    <p class="text-3xl font-bold mt-1 text-gray-800">{{ $positionsCount }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Total Votes Cast</p>
                                    <p class="text-3xl font-bold mt-1 text-gray-800">{{ $totalVotes }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="text-base font-semibold mt-1 text-gray-800">
                                        {{ $ongoingCount }} ongoing, {{ $closedCount }} closed
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('reports.group.pdf', $group) }}"
                               class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v3a1 1 0 001 1h16a1 1 0 001-1v-3" />
                                </svg>
                                Download PDF Report
                            </a>
                        </div>

                    </div>

                @empty

                    <div class="col-span-2 bg-white shadow rounded-2xl p-6">
                        <p class="text-gray-500">
                            No meetings available for reporting.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
