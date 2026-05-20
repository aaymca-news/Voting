<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        h2 {
            font-size: 18px;
            margin-top: 24px;
            margin-bottom: 6px;
        }

        h3 {
            font-size: 15px;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 18px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .muted {
            color: #6b7280;
        }

        .section {
            margin-bottom: 26px;
            page-break-inside: avoid;
        }

        .small {
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <h1>{{ $group->name }} Report</h1>

    <p class="muted">
        {{ $group->description }}
    </p>

    <p class="muted">
        Code: {{ $group->code }}
    </p>

    <p class="muted">
        Generated on: {{ now()->format('M d, Y h:i A') }}
    </p>

    @php
        $hasCompletedMotions = false;
    @endphp

    @foreach($group->elections->where('status', 'closed') as $election)

        @php
            $motion = $election->votingItems->first();
        @endphp

        @if($motion)

            @php
                $hasCompletedMotions = true;

                $totalVotes = $motion->options->sum(function ($option) {
                    return $option->votes->count();
                });

                $highestVotes = $motion->options->max(function ($option) {
                    return $option->votes->count();
                });

                $leadingOptions = $motion->options->filter(function ($option) use ($highestVotes, $totalVotes) {
                    return $totalVotes > 0 && $option->votes->count() === $highestVotes;
                });
            @endphp

            <div class="section">

                <h2>{{ $election->title }}</h2>

                <p class="muted">
                    {{ $election->description }}
                </p>

                <p>
                    <strong>Status:</strong> Closed
                </p>

                <p>
                    <strong>Voting Visibility:</strong>
                    {{ $motion->voting_mode === 'named' ? 'Visible voters' : 'Anonymous voters' }}
                </p>

                <p>
                    <strong>Total Votes Cast:</strong> {{ $totalVotes }}
                </p>

                <p>
                    <strong>Leading Option:</strong>
                    @if($totalVotes > 0)
                        {{ $leadingOptions->pluck('name')->join(', ') }}
                        with {{ $highestVotes }} vote{{ $highestVotes === 1 ? '' : 's' }}
                    @else
                        No votes
                    @endif
                </p>

                <table>
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Votes</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($motion->options as $option)

                            @php
                                $votes = $option->votes->count();

                                $percentage = $totalVotes > 0
                                    ? round(($votes / $totalVotes) * 100, 1)
                                    : 0;
                            @endphp

                            <tr>
                                <td>{{ $option->name }}</td>
                                <td>{{ $votes }}</td>
                                <td>{{ $percentage }}%</td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>

                @if($motion->voting_mode === 'named')

                    <h3>Voter Details</h3>

                    @foreach($motion->options as $option)

                        <table>
                            <thead>
                                <tr>
                                    <th colspan="2">{{ $option->name }}</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($option->votes as $vote)

                                    <tr>
                                        <td>{{ $vote->user?->name ?? 'Deleted user' }}</td>
                                        <td>{{ $vote->user?->email ?? '-' }}</td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="2">No voters selected this option.</td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>

                    @endforeach

                @else

                    <p class="small">
                        Voter identities are hidden because anonymous voting was selected.
                    </p>

                @endif

            </div>

        @endif

    @endforeach

    @if(!$hasCompletedMotions)

        <p>
            No completed motions are available for this meeting yet.
        </p>

    @endif

</body>
</html>