<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    AAYMCA Voting Dashboard
                </h1>

                <p class="text-gray-500 mt-2">
                    Manage elections, motions, users, meetings and voting activity.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">👥</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Meetings
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Create and manage meeting groups.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('groups.index') }}"
                       style="display:inline-block; background:#2563eb; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Create Meeting →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#dcfce7; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">📋</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Motions
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Create and manage motion sessions.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('elections.index') }}"
                       style="display:inline-block; background:#16a34a; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Create Motion →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#dbeafe; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">🗳️</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Elections
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Set up positional elections with candidates and photos.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('positional-elections.index') }}"
                       style="display:inline-block; background:#2563eb; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Manage Elections →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#f3e8ff; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">📊</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Votes
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Track voting activity and turnout.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('votes.index') }}"
                       style="display:inline-block; background:#9333ea; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Manage Votes →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#ffedd5; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">👤</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Users
                    </h2>

                    <p class="text-gray-500 mb-6">
                        View registered platform users.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('users.index') }}"
                       style="display:inline-block; background:#f59e0b; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Manage Users →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Audit Logs
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Track all admin and system activities.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('audit-logs.index') }}"
                       style="display:inline-block; background:#0f172a; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        View Logs →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Election Reports
                    </h2>

                    <p class="text-gray-500 mb-6">
                        View results and download PDF election reports.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('reports.index') }}"
                       style="display:inline-block; background:#dc2626; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Download Reports →
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-8">
                    <div style="width:72px; height:72px; border-radius:999px; background:#fef3c7; display:flex; align-items:center; justify-content:center; margin-bottom:28px;">
                        <span style="font-size:34px;">📄</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">
                        Agendas
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Upload and manage meeting agenda documents.
                    </p>

                    <hr class="mb-6">

                    <a href="{{ route('agendas.index') }}"
                       style="display:inline-block; background:#d97706; color:white; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600;">
                        Manage Agendas →
                    </a>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Total Users
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalUsers }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        {{ $totalVoters }} voters, {{ $totalAdmins }} admins
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Groups
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalGroups }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Voting groups created
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Meetings / Elections
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalElections }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Election sessions created
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Votes Cast
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalVotes }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Total submitted votes
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Motions / Agendas
                    </h2>

                    <p class="text-4xl font-bold mt-3">
                        {{ $totalVotingItems }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Total voting items
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Open Motions
                    </h2>

                    <p class="text-4xl font-bold mt-3 text-green-600">
                        {{ $openMotions }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Currently open for voting
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Closed Motions
                    </h2>

                    <p class="text-4xl font-bold mt-3 text-red-600">
                        {{ $closedMotions }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Voting completed
                    </p>
                </div>

                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-gray-500 font-semibold">
                        Draft Motions
                    </h2>

                    <p class="text-4xl font-bold mt-3 text-gray-600">
                        {{ $draftMotions }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        Not yet opened
                    </p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>