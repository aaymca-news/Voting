<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $group->name }} Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
            margin: 30px 36px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 17px;
            margin-top: 28px;
            margin-bottom: 4px;
            border-bottom: 2px solid #d1d5db;
            padding-bottom: 4px;
        }

        h3 {
            font-size: 14px;
            margin-top: 16px;
            margin-bottom: 4px;
        }

        h4 {
            font-size: 13px;
            margin-top: 14px;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 14px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 7px 9px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }

        .small {
            font-size: 11px;
            color: #6b7280;
        }

        .section {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }

        .status-open {
            color: #16a34a;
            font-weight: bold;
        }

        .status-closed {
            color: #dc2626;
            font-weight: bold;
        }

        .status-draft {
            color: #9ca3af;
            font-weight: bold;
        }

        .leading-check {
            color: #16a34a;
            font-weight: bold;
        }

        .section-heading {
            font-size: 19px;
            font-weight: bold;
            margin-top: 32px;
            margin-bottom: 8px;
            background: #f3f4f6;
            padding: 8px 10px;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ───── HEADER ───── --}}
    <h1>{{ $group->name }} &mdash; Meeting Report</h1>

    <p class="muted">{{ $group->description }}</p>
    <p class="muted">Meeting Code: <strong>{{ $group->code }}</strong></p>
    <p class="muted">Generated on: {{ now()->format('M d, Y h:i A') }}</p>

    @php
        $motions            = $group->elections->where('election_type', 'motion')->values();
        $positionalElections = $group->elections->where('election_type', 'positional')->values();
    @endphp

    {{-- ═══════════════════════════════════════════
         SECTION 1: MOTIONS
    ════════════════════════════════════════════ --}}
    @if($motions->isNotEmpty())

        <div class="section-heading">Section 1: Motions</div>

        @foreach($motions as $election)

            @php
                $motion = $election->votingItems->first();
            @endphp

            @if($motion)

                @php
                    $totalVotes   = $motion->options->sum(fn($o) => $o->votes->count());
                    $highestVotes = $motion->options->max(fn($o) => $o->votes->count()) ?? 0;
                @endphp

                <div class="section">

                    <h2>{{ $election->title }}</h2>

                    @if($election->description)
                        <p class="muted">{{ $election->description }}</p>
                    @endif

                    <p>
                        <strong>Status:</strong>
                        @if($election->status === 'open')
                            <span class="status-open">Voting Open</span>
                        @elseif($election->status === 'closed')
                            <span class="status-closed">Closed</span>
                        @else
                            <span class="status-draft">Draft</span>
                        @endif
                    </p>

                    <p>
                        <strong>Voting Visibility:</strong>
                        {{ $motion->voting_mode === 'named' ? 'Visible voters (Named)' : 'Anonymous voters' }}
                    </p>

                    <p>
                        <strong>Total Votes Cast:</strong> {{ $totalVotes }}
                    </p>

                    {{-- Results table --}}
                    <table>
                        <thead>
                            <tr>
                                <th>Option</th>
                                <th>Votes</th>
                                <th>Percentage</th>
                                <th>Leading</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($motion->options as $option)
                                @php
                                    $votes      = $option->votes->count();
                                    $percentage = $totalVotes > 0
                                        ? round(($votes / $totalVotes) * 100, 1)
                                        : 0;
                                    $isLeading  = $totalVotes > 0 && $votes === $highestVotes;
                                @endphp
                                <tr>
                                    <td>{{ $option->name }}</td>
                                    <td>{{ $votes }}</td>
                                    <td>{{ $percentage }}%</td>
                                    <td>
                                        @if($isLeading)
                                            <span class="leading-check">&#10003;</span>
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Named voter details --}}
                    @if($motion->voting_mode === 'named')

                        <h3>Voter Details</h3>

                        @foreach($motion->options as $option)

                            <h4>{{ $option->name }}</h4>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($option->votes as $vote)
                                        <tr>
                                            <td>{{ $vote->user?->name ?? 'Deleted user' }}</td>
                                            <td>{{ $vote->user?->email ?? '&mdash;' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="muted">No voters selected this option.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @endforeach

                    @else

                        <p class="small">
                            Voter identities are hidden because anonymous voting was selected for this motion.
                        </p>

                    @endif

                </div>

            @endif

        @endforeach

    @endif

    {{-- ═══════════════════════════════════════════
         SECTION 2: POSITIONAL ELECTIONS
    ════════════════════════════════════════════ --}}
    @if($positionalElections->isNotEmpty())

        <div class="section-heading">Section 2: Positional Elections</div>

        @foreach($positionalElections as $election)

            <div class="section">

                <h2>{{ $election->title }}</h2>

                @if($election->description)
                    <p class="muted">{{ $election->description }}</p>
                @endif

                <p>
                    <strong>Status:</strong>
                    @if($election->status === 'open')
                        <span class="status-open">Voting Open</span>
                    @elseif($election->status === 'closed')
                        <span class="status-closed">Closed</span>
                    @else
                        <span class="status-draft">Draft</span>
                    @endif
                </p>

                @forelse($election->votingItems as $position)

                    @php
                        $totalVotes   = $position->options->sum(fn($o) => $o->votes->count());
                        $highestVotes = $position->options->max(fn($o) => $o->votes->count()) ?? 0;
                    @endphp

                    <div class="section">

                        <h3>Position: {{ $position->title ?? $position->name ?? 'Unnamed Position' }}</h3>

                        <p>
                            <strong>Status:</strong>
                            @if(isset($position->status))
                                @if($position->status === 'open')
                                    <span class="status-open">Open</span>
                                @elseif($position->status === 'closed')
                                    <span class="status-closed">Closed</span>
                                @else
                                    <span class="status-draft">{{ ucfirst($position->status) }}</span>
                                @endif
                            @else
                                <span class="muted">—</span>
                            @endif
                        </p>

                        <p><strong>Total Votes Cast:</strong> {{ $totalVotes }}</p>

                        <table>
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Votes</th>
                                    <th>Percentage</th>
                                    <th>Leading</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($position->options as $option)
                                    @php
                                        $votes      = $option->votes->count();
                                        $percentage = $totalVotes > 0
                                            ? round(($votes / $totalVotes) * 100, 1)
                                            : 0;
                                        $isLeading  = $totalVotes > 0 && $votes === $highestVotes;
                                    @endphp
                                    <tr>
                                        <td>{{ $option->name }}</td>
                                        <td>{{ $votes }}</td>
                                        <td>{{ $percentage }}%</td>
                                        <td>
                                            @if($isLeading)
                                                <span class="leading-check">&#10003;</span>
                                            @else
                                                &mdash;
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="muted">No candidates found for this position.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                @empty

                    <p class="muted">No positions defined for this election.</p>

                @endforelse

            </div>

        @endforeach

    @endif

    {{-- Fallback if truly nothing exists --}}
    @if($motions->isEmpty() && $positionalElections->isEmpty())
        <p class="muted" style="margin-top: 24px;">
            No elections or motions have been created for this meeting yet.
        </p>
    @endif

    {{-- ───── FOOTER ───── --}}
    <div class="footer">
        Generated by AAYMCAVoting System
    </div>

</body>
</html>
