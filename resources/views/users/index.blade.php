<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Registered Users
                </h1>

                <p class="text-gray-500 mt-2">
                    View and manage users who have created accounts on the platform.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($users as $user)

                    <div class="border-b last:border-b-0 py-5">

                        <div class="flex justify-between items-center gap-4">

                            <div>
                                <p class="font-bold text-lg text-gray-900">
                                    {{ $user->name }}
                                </p>

                                <p class="text-gray-500">
                                    {{ $user->email }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 flex-wrap justify-end">

                                @if($user->email === 'raymondmunene5@gmail.com')
                                    <span class="bg-black text-white px-4 py-1 rounded-full text-xs">
                                        Super Admin
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs">
                                        Admin
                                    </span>
                                @else
                                    <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs">
                                        Voter
                                    </span>
                                @endif

                                @if(auth()->user()->email === 'raymondmunene5@gmail.com' && $user->email !== 'raymondmunene5@gmail.com')

                                    @if($user->role === 'admin')
                                        <form method="POST" action="{{ route('users.remove-admin', $user) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                                Remove Admin
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('users.make-admin', $user) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                                Make Admin
                                            </button>
                                        </form>
                                    @endif

                                @endif

                                @if(auth()->id() !== $user->id && $user->email !== 'raymondmunene5@gmail.com')

                                    <button type="button"
                                            onclick="document.getElementById('delete-user-box-{{ $user->id }}').style.display='block'"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Remove
                                    </button>

                                @else

                                    @if(auth()->id() === $user->id)
                                        <span class="text-sm text-gray-400">
                                            Current User
                                        </span>
                                    @endif

                                @endif

                            </div>

                        </div>

                        @if(auth()->id() !== $user->id && $user->email !== 'raymondmunene5@gmail.com')

                            <div id="delete-user-box-{{ $user->id }}"
                                 style="display:none;"
                                 class="mt-4 bg-red-50 border border-red-200 rounded-xl p-5">

                                <h3 class="text-lg font-bold text-red-700 mb-2">
                                    Confirm Remove User
                                </h3>

                                <p class="text-red-600 mb-4">
                                    Are you sure you want to permanently remove this user account?
                                </p>

                                <div class="flex gap-3">

                                    <form method="POST"
                                          action="{{ route('users.destroy', $user) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                            Yes, Remove
                                        </button>

                                    </form>

                                    <button type="button"
                                            onclick="document.getElementById('delete-user-box-{{ $user->id }}').style.display='none'"
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                        Cancel
                                    </button>

                                </div>

                            </div>

                        @endif

                    </div>

                @empty

                    <p class="text-gray-500">
                        No registered users found.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>