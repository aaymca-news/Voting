<x-app-layout>
    <meta http-equiv="refresh" content="10">

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Live Voting Results</h1>
                <p class="text-gray-500 mt-1 text-sm">
                    Auto-refreshes every 10 seconds &mdash; Last updated: {{ now()->format('H:i:s') }}
                </p>
            </div>

            {{-- Summary Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white shadow rounded-2xl p-6" style="border-left: 5px solid #3b82f6;">
                    <h2 class="text-lg font-semibold text-gray-600">Motions &amp; Elections</h2>
                    <p class="text-4xl font-bold mt-3" style="color:#3b82f6;">{{ $totalElections }}</p>
                    <p class="text-sm text-gray-500 mt-2">Total elections created</p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6" style="border-left: 5px solid #10b981;">
                    <h2 class="text-lg font-semibold text-gray-600">Voting Items</h2>
                    <p class="text-4xl font-bold mt-3" style="color:#10b981;">{{ $totalVotingItems }}</p>
                    <p class="text-sm text-gray-500 mt-2">Options &amp; positions across all elections</p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6" style="border-left: 5px solid #f59e0b;">
                    <h2 class="text-lg font-semibold text-gray-600">Votes Cast</h2>
                    <p class="text-4xl font-bold mt-3" style="color:#f59e0b;">{{ $totalVotes }}</p>
                    <p class="text-sm text-gray-500 mt-2">Total votes recorded</p>
                </div>

            </div>

            {{-- Elections grouped by meeting --}}
            <div class="space-y-10">

                @forelse($elections->groupBy(fn($e) => optional($e->group)->name ?? 'General') as $meetingName => $meetingElections)

                    @php
                        $firstElection = $meetingElections->first();
                        $meeting = $firstElection->group;
                        $motions = $meetingElections->filter(fn($e) => $e->isMotion());
                        $positionals = $meetingElections->filter(fn($e) => $e->isPositional());
                    @endphp

                    <div class="bg-white shadow rounded-2xl overflow-hidden">

                        {{-- Meeting heading bar --}}
                        <div style="background:#1e3a5f;" class="px-8 py-5">
                            <h2 class="text-2xl font-bold text-white">{{ $meetingName }}</h2>
                            @if($meeting?->description)
                                <p class="text-blue-200 mt-1 text-sm">{{ $meeting->description }}</p>
                            @endif
                        </div>

                        <div class="p-6 space-y-10">

                            {{-- ────────────────────────────────────────────
                                 MOTIONS
                            ──────────────────────────────────────────── --}}
                            @if($motions->isNotEmpty())
                                <div>
                                    <h3 style="font-size:0.85rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.08em; border-bottom:2px solid #e5e7eb; padding-bottom:8px; margin-bottom:20px;">
                                        Motions
                                    </h3>

                                    <div class="space-y-6">
                                        @foreach($motions as $election)

                                            @php
                                                $elStatus = $election->status ?? '';
                                                $statusLabel = match($elStatus) {
                                                    'open', 'voting' => 'Voting Open',
                                                    'closed'         => 'Closed',
                                                    default          => 'Draft',
                                                };
                                                $statusBg = match($elStatus) {
                                                    'open', 'voting' => 'background:#16a34a;color:#fff;',
                                                    'closed'         => 'background:#dc2626;color:#fff;',
                                                    default          => 'background:#6b7280;color:#fff;',
                                                };

                                                $options = $election->votingItems ?? collect();
                                                $totalOptionVotes = $options->sum(fn($o) => $o->votes->count());
                                                $leadingVotes = $options->max(fn($o) => $o->votes->count());
                                            @endphp

                                            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:20px;">

                                                {{-- Motion title row --}}
                                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:6px;">
                                                    <div>
                                                        <h4 style="font-size:1.05rem; font-weight:700; color:#1e3a5f; margin:0 0 4px 0;">
                                                            {{ $election->title }}
                                                        </h4>
                                                        @if($election->description)
                                                            <p style="color:#6b7280; font-size:0.88rem; margin:0;">{{ $election->description }}</p>
                                                        @endif
                                                    </div>
                                                    <span style="white-space:nowrap; font-size:0.78rem; font-weight:600; padding:4px 12px; border-radius:999px; {{ $statusBg }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </div>

                                                {{-- Voting options with bars --}}
                                                <div style="margin-top:16px;">
                                                    @forelse($options as $option)
                                                        @php
                                                            $voteCount = $option->votes->count();
                                                            $pct = $totalOptionVotes > 0 ? round(($voteCount / $totalOptionVotes) * 100, 1) : 0;
                                                            $isLeading = $leadingVotes > 0 && $voteCount === $leadingVotes;
                                                            $barColor = $isLeading ? '#10b981' : '#3b82f6';
                                                        @endphp
                                                        <div style="margin-bottom:14px;">
                                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                                                                <span style="font-weight:{{ $isLeading ? '700' : '500' }}; color:{{ $isLeading ? '#065f46' : '#374151' }}; font-size:0.95rem;">
                                                                    {{ $option->title ?? $option->name ?? 'Option' }}
                                                                    @if($isLeading && $totalOptionVotes > 0)
                                                                        <span style="font-size:0.72rem; background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:999px; margin-left:6px;">Leading</span>
                                                                    @endif
                                                                </span>
                                                                <span style="font-size:0.88rem; color:#374151; font-weight:600;">
                                                                    {{ $voteCount }} vote{{ $voteCount !== 1 ? 's' : '' }} &mdash; {{ $pct }}%
                                                                </span>
                                                            </div>
                                                            <div style="background:#e5e7eb; border-radius:999px; height:10px; overflow:hidden;">
                                                                <div style="height:10px; border-radius:999px; background:{{ $barColor }}; width:{{ $pct }}%;"></div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p style="color:#9ca3af; font-size:0.88rem;">No voting options defined for this motion.</p>
                                                    @endforelse
                                                </div>

                                                {{-- Footer --}}
                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:14px; padding-top:12px; border-top:1px solid #e5e7eb;">
                                                    <span style="font-size:0.85rem; color:#6b7280;">
                                                        Total votes cast: <strong>{{ $totalOptionVotes }}</strong>
                                                    </span>
                                                    <a href="{{ route('votes.results', $election) }}"
                                                       style="font-size:0.82rem; color:#3b82f6; text-decoration:none; font-weight:600; border:1px solid #3b82f6; padding:5px 14px; border-radius:6px;">
                                                        View Full Results &rarr;
                                                    </a>
                                                </div>

                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- ────────────────────────────────────────────
                                 POSITIONAL ELECTIONS
                            ──────────────────────────────────────────── --}}
                            @if($positionals->isNotEmpty())
                                <div>
                                    <h3 style="font-size:0.85rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.08em; border-bottom:2px solid #e5e7eb; padding-bottom:8px; margin-bottom:20px;">
                                        Positional Elections
                                    </h3>

                                    <div class="space-y-6">
                                        @foreach($positionals as $election)

                                            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:20px;">

                                                {{-- Election title --}}
                                                <h4 style="font-size:1.08rem; font-weight:700; color:#1e3a5f; margin:0 0 4px 0;">
                                                    {{ $election->title }}
                                                </h4>
                                                @if($election->description)
                                                    <p style="color:#6b7280; font-size:0.88rem; margin:0 0 18px 0;">{{ $election->description }}</p>
                                                @else
                                                    <div style="margin-bottom:18px;"></div>
                                                @endif

                                                {{-- Positions --}}
                                                @forelse($election->votingItems as $position)

                                                    @php
                                                        $posStatus = $position->status ?? '';
                                                        $posLabel = match($posStatus) {
                                                            'open', 'voting' => 'Open',
                                                            'closed'         => 'Closed',
                                                            default          => 'Draft',
                                                        };
                                                        $posBadge = match($posStatus) {
                                                            'open', 'voting' => 'background:#16a34a;color:#fff;',
                                                            'closed'         => 'background:#dc2626;color:#fff;',
                                                            default          => 'background:#6b7280;color:#fff;',
                                                        };
                                                        $candidates = $position->options ?? collect();
                                                        $totalCandVotes = $candidates->sum(fn($c) => $c->votes->count());
                                                        $leadingCandVotes = $candidates->max(fn($c) => $c->votes->count());
                                                    @endphp

                                                    <div style="background:#fff; border:1px solid #d1d5db; border-radius:8px; padding:16px; margin-bottom:14px;">

                                                        {{-- Position header --}}
                                                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                                                            <span style="font-weight:700; color:#1e3a5f; font-size:0.98rem;">
                                                                {{ $position->title ?? $position->name ?? 'Position' }}
                                                            </span>
                                                            <span style="font-size:0.75rem; font-weight:600; padding:3px 10px; border-radius:999px; {{ $posBadge }}">
                                                                {{ $posLabel }}
                                                            </span>
                                                        </div>

                                                        {{-- Candidates --}}
                                                        @forelse($candidates as $candidate)
                                                            @php
                                                                $candVotes = $candidate->votes->count();
                                                                $candPct = $totalCandVotes > 0 ? round(($candVotes / $totalCandVotes) * 100, 1) : 0;
                                                                $candLeading = $leadingCandVotes > 0 && $candVotes === $leadingCandVotes;
                                                                $candBar = $candLeading ? '#10b981' : '#3b82f6';
                                                            @endphp
                                                            <div style="margin-bottom:12px;">
                                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                                                    <span style="font-weight:{{ $candLeading ? '700' : '500' }}; color:{{ $candLeading ? '#065f46' : '#374151' }}; font-size:0.92rem;">
                                                                        {{ $candidate->title ?? $candidate->name ?? 'Candidate' }}
                                                                        @if($candLeading && $totalCandVotes > 0)
                                                                            <span style="font-size:0.7rem; background:#d1fae5; color:#065f46; padding:2px 7px; border-radius:999px; margin-left:6px;">Leading</span>
                                                                        @endif
                                                                    </span>
                                                                    <span style="font-size:0.85rem; color:#374151; font-weight:600;">
                                                                        {{ $candVotes }} vote{{ $candVotes !== 1 ? 's' : '' }} &mdash; {{ $candPct }}%
                                                                    </span>
                                                                </div>
                                                                <div style="background:#e5e7eb; border-radius:999px; height:9px; overflow:hidden;">
                                                                    <div style="height:9px; border-radius:999px; background:{{ $candBar }}; width:{{ $candPct }}%;"></div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <p style="color:#9ca3af; font-size:0.85rem;">No candidates listed for this position.</p>
                                                        @endforelse

                                                        {{-- Position footer --}}
                                                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:10px; border-top:1px solid #f3f4f6;">
                                                            <span style="font-size:0.82rem; color:#6b7280;">
                                                                Total votes: <strong>{{ $totalCandVotes }}</strong>
                                                            </span>
                                                            <a href="{{ route('positions.results', $position) }}"
                                                               style="font-size:0.8rem; color:#3b82f6; text-decoration:none; font-weight:600; border:1px solid #3b82f6; padding:4px 12px; border-radius:6px;">
                                                                View Results &rarr;
                                                            </a>
                                                        </div>

                                                    </div>

                                                @empty
                                                    <p style="color:#9ca3af; font-size:0.88rem; padding:8px 0;">No positions defined for this election.</p>
                                                @endforelse

                                                {{-- View all results link --}}
                                                <div style="text-align:right; margin-top:10px;">
                                                    <a href="{{ route('votes.results', $election) }}"
                                                       style="font-size:0.85rem; color:#1e3a5f; text-decoration:none; font-weight:600; border:1px solid #1e3a5f; padding:5px 14px; border-radius:6px;">
                                                        View All Results &rarr;
                                                    </a>
                                                </div>

                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($motions->isEmpty() && $positionals->isEmpty())
                                <p style="color:#9ca3af; text-align:center; padding:24px 0; font-size:0.95rem;">
                                    No elections or motions found for this meeting.
                                </p>
                            @endif

                        </div>
                    </div>

                @empty

                    <div class="bg-white shadow rounded-2xl p-10 text-center">
                        <p class="text-gray-400 text-lg">No voting sessions available yet.</p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
