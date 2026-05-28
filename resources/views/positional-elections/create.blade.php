<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Election</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">

                <form method="POST" action="{{ route('positional-elections.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Election Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Meeting</label>
                        <select name="group_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Meeting</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('group_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Start Date</label>
                            <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">End Date</label>
                            <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit"
                            style="background:#2563eb; color:white; padding:10px 24px; border-radius:8px; font-weight:600;">
                            Create Election
                        </button>
                        <a href="{{ route('positional-elections.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
