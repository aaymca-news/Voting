<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $group->name }}
                </h1>

                <p class="text-gray-500 mt-2">
                    {{ $group->description }}
                </p>

                <p class="text-gray-400 text-sm mt-2">
                    Code: {{ $group->code }}
                </p>
            </div>

            <div class="bg-white shadow rounded-2xl p-6 mb-8">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        Group Members
                    </h2>

                    <form method="POST"
                          action="{{ route('group-members.store', $group) }}"
                          class="flex gap-3 items-center">

                        @csrf

                        <select name="user_id"
                                class="border rounded-lg px-4 py-2"
                                required>
                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            style="background:#2563eb; color:white; padding:10px 16px; border:none; border-radius:8px;">
                            Add User
                        </button>
                    </form>
                </div>

                <div class="space-y-3">
                    @forelse($group->users as $user)
                        <div class="border rounded-lg px-4 py-3 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">
                                    {{ $user->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </p>
                            </div>

                            <span style="background:#16a34a; color:white; padding:6px 12px; border-radius:999px; font-size:12px;">
                                Voter
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500">
                            No users added to this group yet.
                        </p>
                    @endforelse
                </div>

            </div>

            <div class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-2xl font-bold mb-6">
                    Elections / Meetings under this Group
                </h2>

                @forelse($group->elections as $election)

                    <div class="border rounded-xl p-6 mb-8">

                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h3 class="text-2xl font-bold">
                                    {{ $election->title }}
                                </h3>

                                <p class="text-gray-500 mt-1">
                                    {{ $election->description }}
                                </p>
                            </div>

                            @if($election->status === 'open')
                                <span style="background:#16a34a; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Open
                                </span>
                            @elseif($election->status === 'closed')
                                <span style="background:#dc2626; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Closed
                                </span>
                            @else
                                <span style="background:#6b7280; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <div class="mt-6">

                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-semibold">
                                    Motions / Agendas
                                </h4>

                                <a href="{{ route('voting-items.create', $election) }}"
                                   style="display:inline-block; background:#2563eb; color:white; padding:8px 14px; border-radius:8px; text-decoration:none;">
                                    Add Motion / Agenda
                                </a>
                            </div>

                            @forelse($election->votingItems as $item)

                                <div class="border rounded-lg p-5 mb-4">

                                    <div class="flex justify-between items-start gap-4">

                                        <div>
                                            <h5 class="text-lg font-bold">
                                                {{ $item->title }}
                                            </h5>

                                            <p class="text-gray-500 mt-1">
                                                {{ $item->description }}
                                            </p>
                                        </div>

                                        @if($item->status === 'open')
                                            <span style="background:#16a34a; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                                Voting Open
                                            </span>
                                        @elseif($item->status === 'closed')
                                            <span style="background:#dc2626; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                                Closed
                                            </span>
                                        @else
                                            <span style="background:#6b7280; color:white; padding:6px 12px; border-radius:999px; font-size:13px;">
                                                Draft
                                            </span>
                                        @endif

                                    </div>

                                    <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap;">

                                        @if($item->status === 'draft')

                                            <a href="{{ route('voting-items.edit', $item) }}"
                                               style="display:inline-block; background:#f59e0b; color:white; padding:8px 14px; border-radius:8px; text-decoration:none;">
                                                Edit
                                            </a>

                                            <button type="button"
                                                    onclick="document.getElementById('delete-box-{{ $item->id }}').style.display='block'"
                                                    style="background:#dc2626; color:white; padding:8px 14px; border:none; border-radius:8px; cursor:pointer;">
                                                Delete
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('voting-items.open', $item) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    style="background:#16a34a; color:white; padding:8px 14px; border:none; border-radius:8px; cursor:pointer;">
                                                    Start Vote
                                                </button>

                                            </form>

                                        @endif

                                        @if($item->status === 'open')

                                            <form method="POST"
                                                  action="{{ route('voting-items.close', $item) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    style="background:#dc2626; color:white; padding:8px 14px; border:none; border-radius:8px; cursor:pointer;">
                                                    Close Vote
                                                </button>

                                            </form>

                                        @endif

                                        <a href="{{ route('votes.results', $election) }}"
                                           style="display:inline-block; background:#9333ea; color:white; padding:8px 14px; border-radius:8px; text-decoration:none;">
                                            View Results
                                        </a>

                                    </div>

                                    @if($item->status === 'draft')

                                        <div id="delete-box-{{ $item->id }}"
                                             style="display:none; margin-top:16px; background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:16px; border-radius:10px;">

                                            <p class="font-semibold mb-2">
                                                Confirm Delete
                                            </p>

                                            <p class="mb-4">
                                                Are you sure you want to delete this motion/agendum? This action cannot be undone.
                                            </p>

                                            <div style="display:flex; gap:10px; flex-wrap:wrap;">

                                                <form method="POST"
                                                      action="{{ route('voting-items.destroy', $item) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            style="background:#dc2626; color:white; padding:8px 14px; border:none; border-radius:8px; cursor:pointer;">
                                                        Yes, Delete
                                                    </button>

                                                </form>

                                                <button type="button"
                                                        onclick="document.getElementById('delete-box-{{ $item->id }}').style.display='none'"
                                                        style="background:#6b7280; color:white; padding:8px 14px; border:none; border-radius:8px; cursor:pointer;">
                                                    Cancel
                                                </button>

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            @empty

                                <p class="text-gray-500">
                                    No motions/agendas added yet.
                                </p>

                            @endforelse

                        </div>

                    </div>

                @empty

                    <p class="text-gray-500">
                        No elections/meetings created under this group yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>