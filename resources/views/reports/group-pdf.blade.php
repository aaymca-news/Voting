<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Group Election Report</title>

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
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

    <h1>{{ $group->name }} Election Report</h1>

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
        $hasClosedMotions = false;
    @endphp

    @foreach($group->elections as $election)

        @php
            $closedMotions = $election->votingItems->where('status', 'closed');
        @endphp

        @if($closedMotions->count() > 0)

            @php
                $hasClosedMotions = true;
            @endphp

            <div class="section">

                <h2>{{ $election->title }}</h2>

                <p class="muted">
                    {{ $election->description }}
                </p>

                @foreach($closedMotions as $motion)

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

                    <h3>{{ $motion->title }}</h3>

                    <p class="muted">
                        {{ $motion->description }}
                    </p>

                    <p>
                        <strong>Status:</strong> Closed
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

                @endforeach

            </div>

        @endif

    @endforeach

    @if(!$hasClosedMotions)

        <p>
            No closed motions or completed election results are available for this group yet.
        </p>

    @endif

</body>
</html>