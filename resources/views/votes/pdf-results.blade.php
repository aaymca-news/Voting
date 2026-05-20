<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Motion Results Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 18px;
            margin-top: 24px;
            margin-bottom: 8px;
        }

        h3 {
            font-size: 15px;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
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

        .summary {
            background: #f9fafb;
            border: 1px solid #d1d5db;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .small {
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    @php
        $motion = $election->votingItems->first();

        $totalVotes = $motion
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
            ? $motion->options->filter(function ($option) use ($highestVotes, $totalVotes) {
                return $totalVotes > 0 && $option->votes->count() === $highestVotes;
            })
            : collect();
    @endphp

    <h1>Motion Results Report</h1>

    <div class="summary">
        <p>
            <strong>Motion:</strong> {{ $election->title }}
        </p>

        <p>
            <strong>Description:</strong> {{ $election->description }}
        </p>

        <p>
            <strong>Status:</strong> {{ ucfirst($election->status) }}
        </p>

        @if($motion)
            <p>
                <strong>Voting Visibility:</strong>
                {{ $motion->voting_mode === 'named' ? 'Visible voters' : 'Anonymous voters' }}
            </p>
        @endif

        <p>
            <strong>Total Votes Cast:</strong> {{ $totalVotes }}
        </p>

        <p>
            <strong>Generated on:</strong> {{ now()->format('M d, Y h:i A') }}
        </p>
    </div>

    @if($motion)

        @if($totalVotes > 0)
            <p>
                <strong>Leading Option:</strong>
                {{ $leadingOptions->pluck('name')->join(', ') }}
                with {{ $highestVotes }} vote{{ $highestVotes === 1 ? '' : 's' }}
            </p>
        @else
            <p class="muted">
                No votes have been cast for this motion yet.
            </p>
        @endif

        <h2>Vote Summary</h2>

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

            <h2>Voter Details</h2>

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

    @else

        <p class="muted">
            No voting data found for this motion.
        </p>

    @endif

</body>
</html>