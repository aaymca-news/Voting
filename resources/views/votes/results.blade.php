<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <meta http-equiv="refresh" content="10">

            {{-- ══════════ POSITIONAL ELECTION RESULTS ══════════ --}}
            @if($election->isPositional())

                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Results: {{ $election->title }}</h1>
                    <p class="text-gray-500 mt-1">{{ $election->description }}</p>
                    <div class="mt-3">
                        <a href="{{ route('positional-elections.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Elections</a>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Auto-refreshes every 10 seconds.</p>
                </div>

                <div class="space-y-8">
                    @forelse($election->votingItems as $position)
                        @php
                            $totalVotes = $position->options->sum(fn ($c) => $c->votes->count());
                            $highest    = $position->options->max(fn ($c) => $c->votes->count());
                        @endphp

                        <div class="bg-white shadow rounded-2xl p-6">

                            <div class="flex justify-between items-start mb-5">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">{{ $position->title }}</h2>
                                    @if($position->description)
                                        <p class="text-gray-500 text-sm mt-1">{{ $position->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 text-sm">{{ $totalVotes }} vote{{ $totalVotes === 1 ? '' : 's' }}</span>
                                    @if($position->status === 'open')
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                    @elseif($position->status === 'closed')
                                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                    @endif
                                </div>
                            </div>

                            @if($totalVotes > 0)
                                @php $winners = $position->options->filter(fn ($c) => $c->votes->count() === $highest && $highest > 0); @endphp
                                <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-3 mb-5">
                                    <p class="text-green-800 font-semibold text-sm">
                                        Leading: {{ $winners->pluck('name')->join(', ') }} — {{ $highest }} vote{{ $highest === 1 ? '' : 's' }}
                                    </p>
                                </div>
                            @else
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 mb-5">
                                    <p class="text-gray-400 text-sm">No votes cast yet.</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach($position->options as $candidate)
                                    @php
                                        $votes     = $candidate->votes->count();
                                        $pct       = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;
                                        $isLeading = $totalVotes > 0 && $votes === $highest;
                                    @endphp
                                    <div class="bg-white rounded-xl border p-4 flex flex-col items-center text-center {{ $isLeading ? 'ring-2 ring-green-400' : '' }}">
                                        @if($isLeading)
                                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">Leading</span>
                                        @endif
                                        @if($candidate->photo_path)
                                            <img src="{{ asset('storage/' . $candidate->photo_path) }}"
                                                 class="w-16 h-16 rounded-full object-cover border-2 {{ $isLeading ? 'border-green-400' : 'border-gray-200' }} mb-2">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border-2 {{ $isLeading ? 'border-green-400' : 'border-gray-200' }} mb-2">
                                                <span class="text-2xl">👤</span>
                                            </div>
                                        @endif
                                        <p class="font-bold text-gray-800 text-sm">{{ $candidate->name }}</p>
                                        <p class="text-2xl font-bold {{ $isLeading ? 'text-green-600' : 'text-gray-700' }} mt-1">{{ $votes }}</p>
                                        <p class="text-gray-400 text-xs">{{ $pct }}%</p>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                            <div class="{{ $isLeading ? 'bg-green-500' : 'bg-blue-400' }} h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @empty
                        <div class="bg-white shadow rounded-2xl p-6">
                            <p class="text-gray-500">No positions found for this election.</p>
                        </div>
                    @endforelse
                </div>

            @else
            {{-- ══════════ MOTION RESULTS (existing) ══════════ --}}

            @php
                $motion = $election->votingItems->first();

                $motionTotalVotes = $motion
                    ? $motion->options->sum(function ($option) {
                        return $option->votes->count();
                    })
                    : 0;

                $highestVotes = $motion
                    ? $motion->options->max(function ($option) {
                        return $option->votes->count();
                    })
                    : 0;

                $leadingOptions = $motion
                    ? $motion->options->filter(function ($option) use ($highestVotes, $motionTotalVotes) {
                        return $motionTotalVotes > 0 && $option->votes->count() === $highestVotes;
                    })
                    : collect();
            @endphp

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

                @if($motion)

                    <div class="border rounded-xl p-6">

                        <div class="flex justify-between items-start gap-4 mb-5">

                            <div>
                                <p class="font-semibold">
                                    Total Votes Cast: {{ $motionTotalVotes }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Voting Visibility:
                                    {{ $motion->voting_mode === 'named' ? 'Visible voters' : 'Anonymous voters' }}
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
                                        -
                                        {{ $percentage }}%
                                    </div>

                                </div>

                                <div style="background:#e5e7eb; border-radius:999px; height:14px; overflow:hidden;">
                                    <div style="background:#16a34a; height:14px; width:{{ $percentage }}%; border-radius:999px;"></div>
                                </div>

                                @if($motion->voting_mode === 'named')

                                    <div style="margin-top:10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px;">

                                        <p class="font-semibold text-sm text-gray-700 mb-2">
                                            Voters who chose {{ $option->name }}:
                                        </p>

                                        @forelse($option->votes as $vote)

                                            <p class="text-sm text-gray-600">
                                                {{ $vote->user?->name ?? 'Deleted user' }}
                                                @if($vote->user)
                                                    ({{ $vote->user->email }})
                                                @endif
                                            </p>

                                        @empty

                                            <p class="text-sm text-gray-500">
                                                No voters selected this option.
                                            </p>

                                        @endforelse

                                    </div>

                                @endif

                            </div>

                        @empty

                            <p class="text-gray-500">
                                No voting options found.
                            </p>

                        @endforelse

                        @if($motion->voting_mode !== 'named')
                            <p class="text-sm text-gray-500">
                                Voter identities are hidden because anonymous voting was selected.
                            </p>
                        @endif

                    </div>

                @else

                    <p class="text-gray-500">
                        No voting data found for this motion.
                    </p>

                @endif

            </div>

            @endif {{-- end @else (motion) --}}

        </div>
    </div>
</x-app-layout>