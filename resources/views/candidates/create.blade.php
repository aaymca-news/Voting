<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Candidate — {{ $position->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">

                <form method="POST" action="{{ route('candidates.store', $position) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Photo upload with preview --}}
                    <div class="mb-6 flex flex-col items-center" x-data="{ preview: null }">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-blue-200 mb-4 bg-blue-50 flex items-center justify-center">
                            <img x-show="preview" :src="preview" class="w-full h-full object-cover">
                            <span x-show="!preview" class="text-5xl text-blue-300">👤</span>
                        </div>
                        <label class="cursor-pointer text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Upload Photo
                            <input type="file" name="photo" accept="image/*" class="hidden"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG or GIF · Max 2MB</p>
                        @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Candidate Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Short Bio / Description</label>
                        <textarea name="description" rows="3" placeholder="A brief introduction about the candidate..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            maxlength="500">{{ old('description') }}</textarea>
                        <p class="text-gray-400 text-xs mt-1">Max 500 characters</p>
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                            style="background:#2563eb; color:white; padding:10px 24px; border-radius:8px; font-weight:600;">
                            Add Candidate
                        </button>
                        <a href="{{ route('positional-elections.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
