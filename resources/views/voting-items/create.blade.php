<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-2xl p-6">

                <h1 class="text-3xl font-bold mb-2">
                    Add Motion / Agenda
                </h1>

                <p class="text-gray-500 mb-6">
                    Election / Meeting:
                    <strong>{{ $election->title }}</strong>
                </p>

                <form method="POST" action="{{ route('voting-items.store', $election) }}">
                    @csrf

                    <input type="hidden" name="type" value="motion">

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Motion / Agenda</label>

                        <input type="text"
                               name="title"
                               class="w-full border-gray-300 rounded-lg"
                               required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-1">Description</label>

                        <textarea name="description"
                                  rows="4"
                                  class="w-full border-gray-300 rounded-lg"></textarea>
                    </div>


                    <div class="mb-6">
    <label class="block font-medium mb-1">
        Voting Mode
    </label>

    <select name="voting_mode"
            class="w-full border-gray-300 rounded-lg"
            required>

        <option value="anonymous">
            Anonymous Voting
        </option>

        <option value="named">
            Named Voting
        </option>

    </select>

    <p class="text-sm text-gray-500 mt-2">
        Anonymous hides voter choices in reports. Named voting allows admin-level accountability.
    </p>
</div>

                    <button type="submit"
                        style="display:inline-block; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                        Save Motion / Agenda
                    </button>

                    <a href="{{ route('elections.index') }}"
                       style="margin-left:12px; color:#555; text-decoration:none;">
                        Cancel
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>