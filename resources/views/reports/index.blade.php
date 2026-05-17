<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Election Reports
                </h1>

                <p class="text-gray-500 mt-2">
                    Download summarized reports by group.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @forelse($groups as $group)

                    @php
                        $closedMotionsCount = $group->elections->sum(function ($election) {
                            return $election->votingItems->count();
                        });

                        $totalVotes = $group->elections->sum(function ($election) {
                            return $election->votingItems->sum(function ($motion) {
                                return $motion->options->sum(function ($option) {
                                    return $option->votes->count();
                                });
                            });
                        });
                    @endphp

                    <div class="bg-white shadow rounded-2xl p-6">

                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $group->name }}
                        </h2>

                        <p class="text-gray-500 mt-2">
                            {{ $group->description }}
                        </p>

                        <p class="text-gray-400 text-sm mt-2">
                            Code: {{ $group->code }}
                        </p>

                        <div class="grid grid-cols-2 gap-4 mt-6">

                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-500">
                                    Closed Motions
                                </p>

                                <p class="text-3xl font-bold mt-1">
                                    {{ $closedMotionsCount }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-500">
                                    Votes Cast
                                </p>

                                <p class="text-3xl font-bold mt-1">
                                    {{ $totalVotes }}
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('reports.group.pdf', $group) }}"
                           style="display:inline-block; margin-top:20px; background:#dc2626; color:white; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:600;">
                            Download Group Report
                        </a>

                    </div>

                @empty

                    <div class="bg-white shadow rounded-2xl p-6">
                        <p class="text-gray-500">
                            No groups available for reporting.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>