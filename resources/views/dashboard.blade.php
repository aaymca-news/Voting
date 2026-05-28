<x-app-layout>
    <div style="background:#f1f5f9; min-height:100vh; padding:36px 0 56px;">
        <div style="max-width:1280px; margin:0 auto; padding:0 24px;">

            {{-- ── HEADER ── --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:36px;">
                <div>
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 6px 0;">AAYMCA Voting Platform</p>
                    <h1 style="font-size:26px; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.02em;">Admin Dashboard</h1>
                </div>
                <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:10px 16px; display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:50%; background:#e0e7ff; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">👤</div>
                    <div>
                        <p style="font-size:13px; font-weight:700; color:#0f172a; margin:0; line-height:1.3;">{{ auth()->user()->name }}</p>
                        <p style="font-size:11px; color:#94a3b8; margin:0;">Super Administrator</p>
                    </div>
                </div>
            </div>

            {{-- ── TOP STATS ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" style="margin-bottom:32px;">

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                        <div style="width:42px; height:42px; border-radius:11px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:19px;">👥</div>
                        <span style="font-size:10px; font-weight:700; color:#cbd5e1; text-transform:uppercase; letter-spacing:0.07em;">Users</span>
                    </div>
                    <p style="font-size:34px; font-weight:800; color:#0f172a; margin:0 0 3px; line-height:1;">{{ $totalUsers }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">{{ $totalVoters }} voters · {{ $totalAdmins }} admins</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                        <div style="width:42px; height:42px; border-radius:11px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:19px;">🏛️</div>
                        <span style="font-size:10px; font-weight:700; color:#cbd5e1; text-transform:uppercase; letter-spacing:0.07em;">Groups</span>
                    </div>
                    <p style="font-size:34px; font-weight:800; color:#0f172a; margin:0 0 3px; line-height:1;">{{ $totalGroups }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Voting groups created</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                        <div style="width:42px; height:42px; border-radius:11px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:19px;">🗳️</div>
                        <span style="font-size:10px; font-weight:700; color:#cbd5e1; text-transform:uppercase; letter-spacing:0.07em;">Sessions</span>
                    </div>
                    <p style="font-size:34px; font-weight:800; color:#0f172a; margin:0 0 3px; line-height:1;">{{ $totalElections }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Election sessions</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                        <div style="width:42px; height:42px; border-radius:11px; background:#faf5ff; display:flex; align-items:center; justify-content:center; font-size:19px;">📊</div>
                        <span style="font-size:10px; font-weight:700; color:#cbd5e1; text-transform:uppercase; letter-spacing:0.07em;">Votes</span>
                    </div>
                    <p style="font-size:34px; font-weight:800; color:#0f172a; margin:0 0 3px; line-height:1;">{{ $totalVotes }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Total votes cast</p>
                </div>

            </div>

            {{-- ── QUICK ACCESS LABEL ── --}}
            <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.09em; margin:0 0 14px;">Quick Access</p>

            {{-- ── ACTION CARDS ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" style="margin-bottom:32px;">

                {{-- Meetings --}}
                <a href="{{ route('groups.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#2563eb; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">🤝</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Meetings</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Manage meeting groups and members</p>
                    <span style="font-size:12px; font-weight:700; color:#2563eb;">Manage →</span>
                </a>

                {{-- Motions --}}
                <a href="{{ route('elections.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#16a34a; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">✋</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Motions</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Create and manage motion sessions</p>
                    <span style="font-size:12px; font-weight:700; color:#16a34a;">Manage →</span>
                </a>

                {{-- Elections --}}
                <a href="{{ route('positional-elections.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#4f46e5; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">🗳️</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Elections</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Positional elections with candidates</p>
                    <span style="font-size:12px; font-weight:700; color:#4f46e5;">Manage →</span>
                </a>

                {{-- Votes --}}
                <a href="{{ route('votes.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#9333ea; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#faf5ff; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">📊</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Votes</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Track voting activity and turnout</p>
                    <span style="font-size:12px; font-weight:700; color:#9333ea;">Manage →</span>
                </a>

                {{-- Users --}}
                <a href="{{ route('users.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#d97706; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#fffbeb; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">👤</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Users</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">View and manage registered users</p>
                    <span style="font-size:12px; font-weight:700; color:#d97706;">Manage →</span>
                </a>

                {{-- Audit Logs --}}
                <a href="{{ route('audit-logs.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#475569; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">🔍</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Audit Logs</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Track all admin and system activity</p>
                    <span style="font-size:12px; font-weight:700; color:#475569;">View Logs →</span>
                </a>

                {{-- Reports --}}
                <a href="{{ route('reports.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#dc2626; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#fef2f2; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">📈</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Reports</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Download PDF election results</p>
                    <span style="font-size:12px; font-weight:700; color:#dc2626;">Download →</span>
                </a>

                {{-- Agendas --}}
                <a href="{{ route('agendas.index') }}"
                   style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:22px; text-decoration:none; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; right:0; height:3px; background:#ea580c; border-radius:14px 14px 0 0;"></div>
                    <div style="width:46px; height:46px; border-radius:12px; background:#fff7ed; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;">📄</div>
                    <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 5px;">Agendas</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0 0 18px; line-height:1.5; flex:1;">Upload and manage agenda documents</p>
                    <span style="font-size:12px; font-weight:700; color:#ea580c;">Manage →</span>
                </a>

            </div>

            {{-- ── MOTION OVERVIEW LABEL ── --}}
            <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.09em; margin:0 0 14px;">Motion Overview</p>

            {{-- ── MOTION STATUS CARDS ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="width:42px; height:42px; border-radius:11px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:19px; margin-bottom:14px;">📋</div>
                    <p style="font-size:34px; font-weight:800; color:#0f172a; margin:0 0 3px; line-height:1;">{{ $totalVotingItems }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Total voting items</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="width:42px; height:42px; border-radius:11px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:19px; margin-bottom:14px;">✅</div>
                    <p style="font-size:34px; font-weight:800; color:#16a34a; margin:0 0 3px; line-height:1;">{{ $openMotions }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Open for voting</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="width:42px; height:42px; border-radius:11px; background:#fef2f2; display:flex; align-items:center; justify-content:center; font-size:19px; margin-bottom:14px;">🔒</div>
                    <p style="font-size:34px; font-weight:800; color:#dc2626; margin:0 0 3px; line-height:1;">{{ $closedMotions }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Voting completed</p>
                </div>

                <div style="background:white; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px;">
                    <div style="width:42px; height:42px; border-radius:11px; background:#fefce8; display:flex; align-items:center; justify-content:center; font-size:19px; margin-bottom:14px;">✏️</div>
                    <p style="font-size:34px; font-weight:800; color:#ca8a04; margin:0 0 3px; line-height:1;">{{ $draftMotions }}</p>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Not yet opened</p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
