<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-2xl p-6">

                <h1 class="text-3xl font-bold mb-2">
                    {{ $election->title }}
                </h1>

                <p class="text-gray-500 mb-8">
                    {{ $election->description }}
                </p>

                <form method="POST" action="{{ route('votes.store', $election) }}">
                    @csrf

                    @foreach($election->votingItems as $item)

                        <div class="border rounded-xl p-5 mb-6">

                            <div class="mb-4">

                                <h2 class="text-2xl font-bold">
                                    {{ $item->title }}
                                </h2>

                                <p class="text-gray-500 mt-1">
                                    {{ ucfirst($item->type) }} Vote
                                </p>

                                @if($item->description)
                                    <p class="text-gray-600 mt-2">
                                        {{ $item->description }}
                                    </p>
                                @endif

                            </div>

                            <div class="space-y-3">

                                @foreach($item->options as $option)

                                    <label class="flex items-start gap-3 border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">

                                        <input
                                            type="radio"
                                            name="votes[{{ $item->id }}]"
                                            value="{{ $option->id }}"
                                            class="mt-1"
                                            required
                                        >

                                        <div>

                                            <div class="font-semibold text-lg">
                                                {{ $option->name }}
                                            </div>

                                            @if($option->description)
                                                <div class="text-gray-500 text-sm mt-1">
                                                    {{ $option->description }}
                                                </div>
                                            @endif

                                        </div>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                    <div class="mt-8 flex items-center gap-4">

                        <button type="submit"
                            style="display:inline-block; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                            Submit Votes
                        </button>

                        <a href="{{ route('elections.index') }}"
                           style="color:#555; text-decoration:none;">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>