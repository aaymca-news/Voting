<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Election
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('elections.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Election Title
                        </label>

                        <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Group
                        </label>

                        <select name="group_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select Group</option>

                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Description
                        </label>

                        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Status
                        </label>

                        <select name="status" class="w-full border rounded px-3 py-2">
                            <option value="draft">Draft</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Start Date
                        </label>

                        <input type="datetime-local" name="starts_at" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            End Date
                        </label>

                        <input type="datetime-local" name="ends_at" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            style="display:inline-block; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                            Save Election
                        </button>

                        <a href="{{ route('elections.index') }}"
                           style="margin-left:12px; color:#555; text-decoration:none;">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>