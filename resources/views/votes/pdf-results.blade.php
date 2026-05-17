<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Voting Results Report</title>

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
            margin-top: 25px;
            margin-bottom: 4px;
        }

        h3 {
            font-size: 15px;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
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
            margin-bottom: 25px;
            page-break-inside: avoid;
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

    <h1>Voting Results Report</h1>

    <div class="summary">
        <p>
            <strong>Election / Meeting:</strong> {{ $election->title }}
        </p>

        <p>
            <strong>Description:</strong> {{ $election->description }}
        </p>

        <p>
            <strong>Generated on:</strong> {{ now()->format('M d, Y h:i A') }}
        </p>
    </div>

    @forelse($election->votingItems as $motion)

        @php
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

            <h2>{{ $motion->title }}</h2>

            <p class="muted">
                {{ $motion->description }}
            </p>

            <p>
                <strong>Status:</strong> {{ ucfirst($motion->status) }}
            </p>

            <p>
                <strong>Total Votes Cast:</strong> {{ $totalVotes }}
            </p>

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

            <p class="small">
                This report shows vote totals only. Voter identities are not displayed in this PDF report.
            </p>

        </div>

    @empty

        <p class="muted">
            No motions/agendas found for this election.
        </p>

    @endforelse

</body>
</html>