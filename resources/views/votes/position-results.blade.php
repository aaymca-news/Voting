<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <meta http-equiv="refresh" content="10">

            @php
                $totalVotes = $position->options->sum(fn ($c) => $c->votes->count());
                $highest    = $position->options->max(fn ($c) => $c->votes->count());
            @endphp

            {{-- Header --}}
            <div class="mb-6">
                <p class="text-sm text-gray-400 mb-1">
                    {{ $position->election->group->name ?? '' }} › {{ $position->election->title }}
                </p>
                <h1 class="text-3xl font-bold text-gray-800">Results: {{ $position->title }}</h1>

                @if($position->description)
                    <p class="text-gray-500 mt-1">{{ $position->description }}</p>
                @endif

                <div class="flex items-center gap-4 mt-4">
                    @if($position->status === 'open')
                        <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">Voting Open</span>
                    @elseif($position->status === 'closed')
                        <span class="bg-gray-100 text-gray-600 text-sm font-semibold px-3 py-1 rounded-full">Voting Closed</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">Draft</span>
                    @endif
                    <span class="text-gray-500 text-sm">Total votes: <strong>{{ $totalVotes }}</strong></span>
                    <span class="text-gray-400 text-xs">Auto-refreshes every 10 seconds</span>
                </div>

                <div class="mt-4">
                    <a href="{{ route('positional-elections.index') }}"
                       class="text-blue-600 hover:underline text-sm">← Back to Elections</a>
                </div>
            </div>

            {{-- Leading banner --}}
            @if($totalVotes > 0)
                @php $winners = $position->options->filter(fn ($c) => $c->votes->count() === $highest && $highest > 0); @endphp
                <div class="bg-green-50 border border-green-200 rounded-xl px-6 py-4 mb-6">
                    <p class="text-green-800 font-semibold">
                        Leading: {{ $winners->pluck('name')->join(', ') }}
                        — {{ $highest }} vote{{ $highest === 1 ? '' : 's' }}
                    </p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl px-6 py-4 mb-6">
                    <p class="text-gray-500">No votes have been cast yet.</p>
                </div>
            @endif

            {{-- Candidate result cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($position->options as $candidate)
                    @php
                        $votes      = $candidate->votes->count();
                        $pct        = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;
                        $isLeading  = $totalVotes > 0 && $votes === $highest;
                    @endphp

                    <div class="bg-white rounded-2xl shadow border p-5 flex flex-col items-center text-center
                                {{ $isLeading ? 'ring-2 ring-green-400' : '' }}">

                        @if($isLeading)
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full mb-3">Leading</span>
                        @endif

                        @if($candidate->photo_path)
                            <img src="{{ asset('storage/' . $candidate->photo_path) }}"
                                 alt="{{ $candidate->name }}"
                                 class="w-24 h-24 rounded-full object-cover border-4 {{ $isLeading ? 'border-green-400' : 'border-gray-200' }} mb-4">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center border-4 {{ $isLeading ? 'border-green-400' : 'border-gray-200' }} mb-4">
                                <span class="text-4xl">👤</span>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-800">{{ $candidate->name }}</h3>

                        @if($candidate->description)
                            <p class="text-gray-400 text-xs mt-1 mb-3">{{ $candidate->description }}</p>
                        @endif

                        <p class="text-3xl font-bold {{ $isLeading ? 'text-green-600' : 'text-gray-700' }} mt-2">
                            {{ $votes }}
                        </p>
                        <p class="text-gray-400 text-sm">vote{{ $votes === 1 ? '' : 's' }} · {{ $pct }}%</p>

                        {{-- Progress bar --}}
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="{{ $isLeading ? 'bg-green-500' : 'bg-blue-400' }} h-2 rounded-full transition-all"
                                 style="width: {{ $pct }}%"></div>
                        </div>

                        {{-- Named voters --}}
                        @if($position->voting_mode === 'named' && $candidate->votes->isNotEmpty())
                            <div class="mt-4 w-full text-left bg-gray-50 rounded-lg p-3 text-xs">
                                <p class="font-semibold text-gray-600 mb-1">Voters:</p>
                                @foreach($candidate->votes as $vote)
                                    <p class="text-gray-500">{{ $vote->user?->name ?? 'Deleted user' }}</p>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
