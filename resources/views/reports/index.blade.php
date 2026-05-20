<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Reports
                </h1>

                <p class="text-gray-500 mt-2">
                    Download completed motion reports by meeting.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @forelse($groups as $group)

                    @php
                        $completedMotionsCount = $group->elections
                            ->where('status', 'closed')
                            ->count();

                        $totalVotes = $group->elections
                            ->where('status', 'closed')
                            ->sum(function ($election) {
                                $motion = $election->votingItems->first();

                                if (!$motion) {
                                    return 0;
                                }

                                return $motion->options->sum(function ($option) {
                                    return $option->votes->count();
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
                                    Completed Motions
                                </p>

                                <p class="text-3xl font-bold mt-1">
                                    {{ $completedMotionsCount }}
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
                            Download Report
                        </a>

                    </div>

                @empty

                    <div class="bg-white shadow rounded-2xl p-6">
                        <p class="text-gray-500">
                            No meetings available for reporting.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>