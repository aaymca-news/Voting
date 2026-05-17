<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Audit Logs
                </h1>

                <p class="text-gray-500 mt-2">
                    Track all important admin activities across the voting platform.
                </p>
            </div>

            <div class="bg-white shadow rounded-2xl overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                    Admin/User
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                    Action
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                    Description
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                    Date & Time
                                </th>

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            @forelse($logs as $log)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-gray-800">
                                            {{ $log->user?->name ?? 'System' }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $log->user?->email }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4">

                                        <span style="background:#2563eb; color:white; padding:6px 10px; border-radius:999px; font-size:12px; text-transform:capitalize;">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-gray-700">

                                        {{ $log->description }}

                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        <div>
                                            {{ $log->created_at->format('M d, Y') }}
                                        </div>

                                        <div>
                                            {{ $log->created_at->format('h:i A') }}
                                        </div>

                                        <div class="text-xs mt-1">
                                            {{ $log->created_at->diffForHumans() }}
                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No audit logs recorded yet.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>