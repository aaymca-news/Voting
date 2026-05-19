<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <meta http-equiv="refresh" content="10">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Results: {{ $election->title }}
                </h1>

                <p class="text-gray-500 mt-2">
                    {{ $election->description }}
                </p>

                <div style="margin-top:20px; margin-bottom:24px;">

    <a href="{{ route('votes.results.pdf', $election) }}"
       style="display:inline-block;
              background:#dc2626;
              color:white;
              padding:10px 18px;
              border-radius:8px;
              text-decoration:none;
              font-weight:600;">

        Download PDF Report

    </a>

</div>

                <p class="text-sm text-gray-400 mt-2">
                    Auto-refreshes every 10 seconds.
                </p>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($election->votingItems as $motion)

                    @php
                        $motionTotalVotes = $motion->options->sum(function ($option) {
                            return $option->votes->count();
                        });

                        $highestVotes = $motion->options->max(function ($option) {
                            return $option->votes->count();
                        });

                        $leadingOptions = $motion->options->filter(function ($option) use ($highestVotes, $motionTotalVotes) {
                            return $motionTotalVotes > 0 && $option->votes->count() === $highestVotes;
                        });
                    @endphp

                    <div class="border rounded-xl p-6 mb-8">

                        <div class="flex justify-between items-start gap-4 mb-5">

                            <div>
                                <h2 class="text-2xl font-bold">
                                    {{ $motion->title }}
                                </h2>

                                <p class="text-gray-500 mt-1">
                                    {{ $motion->description }}
                                </p>

                                <p class="font-semibold mt-4">
                                    Total Votes Cast: {{ $motionTotalVotes }}
                                </p>
                            </div>

                            @if($motion->status === 'open')
                                <span style="background:#16a34a; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Voting Open
                                </span>
                            @elseif($motion->status === 'closed')
                                <span style="background:#dc2626; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Closed
                                </span>
                            @else
                                <span style="background:#6b7280; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Draft
                                </span>
                            @endif

                        </div>

                        @if($motionTotalVotes > 0)

                            <div style="background:#f0fdf4; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
                                Leading:
                                <strong>
                                    {{ $leadingOptions->pluck('name')->join(', ') }}
                                </strong>
                                with {{ $highestVotes }} vote{{ $highestVotes === 1 ? '' : 's' }}.
                            </div>

                        @else

                            <div style="background:#f9fafb; color:#6b7280; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
                                No votes have been cast for this motion yet.
                            </div>

                        @endif

                        @forelse($motion->options as $option)

                            @php
                                $optionVotes = $option->votes->count();

                                $percentage = $motionTotalVotes > 0
                                    ? round(($optionVotes / $motionTotalVotes) * 100, 1)
                                    : 0;

                                $isLeading = $motionTotalVotes > 0 && $optionVotes === $highestVotes;
                            @endphp

                            <div class="mb-5">

                                <div class="flex justify-between items-center mb-1">

                                    <div class="font-semibold">
                                        {{ $option->name }}

                                        @if($isLeading)
                                            <span style="background:#16a34a; color:white; padding:3px 8px; border-radius:999px; font-size:12px; margin-left:8px;">
                                                Leading
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        {{ $optionVotes }} vote{{ $optionVotes === 1 ? '' : 's' }}
                                        —
                                        {{ $percentage }}%
                                    </div>

                                </div>

                                <div style="background:#e5e7eb; border-radius:999px; height:14px; overflow:hidden;">
                                    <div style="background:#16a34a; height:14px; width:{{ $percentage }}%; border-radius:999px;"></div>
                                </div>

                            </div>

                        @empty

                            <p class="text-gray-500">
                                No voting options found for this motion.
                            </p>

                        @endforelse

                    </div>

                @empty

                    <p class="text-gray-500">
                        No motions/agendas found for this election.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>