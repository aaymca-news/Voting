<x-app-layout>
    <div class="py-8">
        <div style="max-width:860px; margin:0 auto; padding:0 20px;">

            {{-- Page header --}}
            <div style="margin-bottom:28px;">
                <h1 style="font-size:28px; font-weight:800; color:#111827; margin:0;">My Voting Portal</h1>
                <p style="color:#6b7280; font-size:14px; margin:6px 0 0 0;">
                    Welcome, <strong>{{ auth()->user()->name }}</strong>. Your assigned meetings and voting items are listed below.
                </p>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div style="background:#dcfce7; color:#166534; border:1px solid #86efac; border-radius:10px; padding:13px 18px; margin-bottom:20px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <span style="font-size:18px;">✓</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; border-radius:10px; padding:13px 18px; margin-bottom:20px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <span style="font-size:18px;">⚠</span> {{ session('error') }}
                </div>
            @endif

            @forelse($groups as $group)

                {{-- ═══════════ MEETING CARD ═══════════ --}}
                <div style="background:white; border-radius:18px; box-shadow:0 2px 16px rgba(0,0,0,0.08); margin-bottom:28px; overflow:hidden;">

                    {{-- Meeting header --}}
                    <div style="background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%); padding:20px 28px;">
                        <h2 style="color:white; font-size:20px; font-weight:800; margin:0 0 3px 0;">{{ $group->name }}</h2>
                        @if($group->description)
                            <p style="color:#bfdbfe; font-size:13px; margin:0 0 3px 0;">{{ $group->description }}</p>
                        @endif
                        <p style="color:#93c5fd; font-size:12px; margin:0;">Meeting Code: {{ $group->code }}</p>
                    </div>

                    {{-- Elections & Motions --}}
                    <div style="padding:20px 24px; display:flex; flex-direction:column; gap:14px;">

                        @forelse($group->elections as $election)

                            {{-- ══════════ MOTION ══════════ --}}
                            @if($election->isMotion())
                                @php $motion = $election->votingItems->first(); @endphp
                                @if($motion)
                                    @php $userVote = $motion->votes->first(); @endphp

                                    <div style="border:1px solid #e5e7eb; border-radius:13px; overflow:hidden;">

                                        {{-- Motion title row --}}
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:14px 18px 12px; gap:12px; flex-wrap:wrap; border-bottom:1px solid #f3f4f6;">
                                            <div>
                                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:3px;">
                                                    <span style="background:#f3f4f6; color:#374151; font-size:10px; font-weight:700; padding:2px 9px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">Motion</span>
                                                    <span style="font-size:16px; font-weight:700; color:#111827;">{{ $election->title }}</span>
                                                </div>
                                                @if($election->description)
                                                    <p style="color:#6b7280; font-size:13px; margin:0;">{{ $election->description }}</p>
                                                @endif
                                            </div>
                                            @if($userVote)
                                                <span style="background:#dcfce7; color:#15803d; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px; white-space:nowrap; flex-shrink:0;">✓ Voted</span>
                                            @elseif($motion->status === 'open')
                                                <span style="background:#dcfce7; color:#15803d; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px; white-space:nowrap; flex-shrink:0; animation:pulse 2s infinite;">● Voting Open</span>
                                            @elseif($motion->status === 'closed')
                                                <span style="background:#f3f4f6; color:#6b7280; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px; white-space:nowrap; flex-shrink:0;">Closed</span>
                                            @else
                                                <span style="background:#fef9c3; color:#854d0e; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px; white-space:nowrap; flex-shrink:0;">Not Started</span>
                                            @endif
                                        </div>

                                        <div style="padding:14px 18px;">
                                            @if($userVote)
                                                {{-- Compact voted confirmation --}}
                                                <div style="display:flex; align-items:center; gap:12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 16px;">
                                                    <div style="width:40px; height:40px; border-radius:50%; background:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                        <span style="color:white; font-size:18px;">✓</span>
                                                    </div>
                                                    <div>
                                                        <p style="font-size:11px; color:#16a34a; font-weight:600; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Your vote</p>
                                                        <p style="font-size:18px; font-weight:800; color:#14532d; margin:0;">{{ $userVote->electionOption->name }}</p>
                                                    </div>
                                                </div>

                                            @elseif($motion->status === 'open')
                                                <form method="POST" action="{{ route('votes.store', $election) }}">
                                                    @csrf
                                                    <input type="hidden" name="voting_item_id" value="{{ $motion->id }}">
                                                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:14px;">
                                                        @foreach($motion->options as $option)
                                                            <label style="display:flex; align-items:center; gap:12px; border:2px solid #e5e7eb; border-radius:10px; padding:11px 14px; cursor:pointer; background:white; transition:all 0.15s;"
                                                                   onmouseover="this.style.borderColor='#93c5fd'; this.style.background='#f8faff';"
                                                                   onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#e5e7eb'; this.style.background='white';}"
                                                                   onclick="this.closest('form').querySelectorAll('label').forEach(l=>{l.style.borderColor='#e5e7eb';l.style.background='white';}); this.style.borderColor='#2563eb'; this.style.background='#eff6ff';">
                                                                <input type="radio" name="election_option_id" value="{{ $option->id }}" required
                                                                       style="width:18px; height:18px; accent-color:#2563eb; flex-shrink:0; cursor:pointer;">
                                                                <span style="font-weight:600; color:#1f2937; font-size:15px;">{{ $option->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <button type="button"
                                                            onclick="document.getElementById('confirm-motion-{{ $motion->id }}').style.display='block'"
                                                            style="background:#2563eb; color:white; padding:10px 22px; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:14px;">
                                                        Submit Vote
                                                    </button>
                                                    <div id="confirm-motion-{{ $motion->id }}"
                                                         style="display:none; margin-top:14px; background:#fff7ed; border:1px solid #fdba74; color:#9a3412; padding:16px; border-radius:10px;">
                                                        <p style="font-weight:700; margin:0 0 5px 0; font-size:15px;">Confirm Your Vote</p>
                                                        <p style="font-size:13px; margin:0 0 12px 0;">Are you sure? Once submitted, you cannot change your vote.</p>
                                                        <div style="display:flex; gap:8px;">
                                                            <button type="submit" style="background:#16a34a; color:white; padding:8px 18px; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Yes, Submit</button>
                                                            <button type="button" onclick="document.getElementById('confirm-motion-{{ $motion->id }}').style.display='none'"
                                                                    style="background:#6b7280; color:white; padding:8px 18px; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            @else
                                                <p style="color:#9ca3af; font-style:italic; font-size:13px; margin:0;">Voting for this motion is currently closed.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            {{-- ══════════ POSITIONAL ELECTION ══════════ --}}
                            @elseif($election->isPositional())

                                <div style="border:2px solid #dbeafe; border-radius:13px; overflow:hidden;">

                                    {{-- Election sub-header --}}
                                    <div style="background:#eff6ff; padding:12px 18px; display:flex; align-items:center; gap:10px;">
                                        <span style="background:#2563eb; color:white; font-size:10px; font-weight:700; padding:3px 10px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em; flex-shrink:0;">Election</span>
                                        <div>
                                            <p style="font-size:15px; font-weight:800; color:#1e3a8a; margin:0;">{{ $election->title }}</p>
                                            @if($election->description)
                                                <p style="font-size:12px; color:#3b82f6; margin:1px 0 0 0;">{{ $election->description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Positions list --}}
                                    <div style="padding:14px 18px; display:flex; flex-direction:column; gap:12px;">

                                        @if($election->votingItems->isEmpty())
                                            <p style="color:#9ca3af; font-style:italic; font-size:13px; margin:0;">No positions are currently open for voting.</p>
                                        @else
                                            @foreach($election->votingItems as $position)
                                                @php $userVote = $position->votes->first(); @endphp

                                                <div style="border:1px solid #e5e7eb; border-radius:11px; overflow:hidden; background:white;">

                                                    {{-- Position header --}}
                                                    <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 16px; border-bottom:1px solid #f3f4f6; gap:8px; flex-wrap:wrap;">
                                                        <h4 style="font-size:15px; font-weight:700; color:#1f2937; margin:0;">{{ $position->title }}</h4>
                                                        <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                                                            @if($userVote)
                                                                <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px;">✓ Voted</span>
                                                            @endif
                                                            @if($position->status === 'open')
                                                                <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px;">Open</span>
                                                            @elseif($position->status === 'closed')
                                                                <span style="background:#f3f4f6; color:#6b7280; font-size:11px; font-weight:700; padding:3px 10px; border-radius:999px;">Closed</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div style="padding:12px 16px;">
                                                        @if($position->description)
                                                            <p style="color:#6b7280; font-size:12px; margin:0 0 10px 0;">{{ $position->description }}</p>
                                                        @endif

                                                        @if($userVote)
                                                            {{-- Compact voted confirmation --}}
                                                            <div style="display:flex; align-items:center; gap:12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:10px 14px;">
                                                                @if($userVote->electionOption->photo_path)
                                                                    <img src="{{ asset('storage/' . $userVote->electionOption->photo_path) }}"
                                                                         style="width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid #4ade80; flex-shrink:0; display:block;">
                                                                @else
                                                                    <div style="width:46px; height:46px; border-radius:50%; background:#dcfce7; border:2px solid #4ade80; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:20px;">👤</div>
                                                                @endif
                                                                <div>
                                                                    <p style="font-size:11px; color:#16a34a; font-weight:600; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Your vote</p>
                                                                    <p style="font-size:16px; font-weight:800; color:#14532d; margin:0;">{{ $userVote->electionOption->name }}</p>
                                                                </div>
                                                            </div>

                                                        @elseif($position->status === 'open')
                                                            @php $candidateCount = $position->options->count(); @endphp

                                                            @if($candidateCount === 1)
                                                                @php $onlyCandidate = $position->options->first(); @endphp
                                                                <form method="POST" action="{{ route('votes.store', $election) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="voting_item_id" value="{{ $position->id }}">
                                                                    <input type="hidden" name="election_option_id" value="{{ $onlyCandidate->id }}">
                                                                    <div style="display:flex; align-items:center; gap:12px; border:2px solid #bfdbfe; border-radius:10px; padding:11px 14px; background:#eff6ff; margin-bottom:12px;">
                                                                        @if($onlyCandidate->photo_path)
                                                                            <img src="{{ asset('storage/' . $onlyCandidate->photo_path) }}"
                                                                                 style="width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid #93c5fd; flex-shrink:0; display:block;">
                                                                        @else
                                                                            <div style="width:46px; height:46px; border-radius:50%; background:#dbeafe; border:2px solid #93c5fd; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:20px;">👤</div>
                                                                        @endif
                                                                        <div>
                                                                            <p style="font-weight:700; color:#1e3a8a; font-size:15px; margin:0;">{{ $onlyCandidate->name }}</p>
                                                                            @if($onlyCandidate->description)
                                                                                <p style="color:#3b82f6; font-size:12px; margin:2px 0 0 0;">{{ $onlyCandidate->description }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <button type="button"
                                                                            onclick="document.getElementById('confirm-pos-{{ $position->id }}').style.display='block'"
                                                                            style="background:#2563eb; color:white; padding:9px 20px; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:14px;">
                                                                        Vote for {{ $onlyCandidate->name }}
                                                                    </button>
                                                                    <div id="confirm-pos-{{ $position->id }}"
                                                                         style="display:none; margin-top:12px; background:#eff6ff; border:1px solid #93c5fd; color:#1e3a8a; padding:15px; border-radius:10px;">
                                                                        <p style="font-weight:700; margin:0 0 5px 0; font-size:14px;">Confirm Your Vote</p>
                                                                        <p style="font-size:13px; margin:0 0 12px 0;">Once submitted, you cannot change your vote for this position.</p>
                                                                        <div style="display:flex; gap:8px;">
                                                                            <button type="submit" style="background:#16a34a; color:white; padding:8px 18px; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Yes, Submit</button>
                                                                            <button type="button" onclick="document.getElementById('confirm-pos-{{ $position->id }}').style.display='none'"
                                                                                    style="background:#6b7280; color:white; padding:8px 18px; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>

                                                            @else
                                                                {{-- Multiple candidates — ballot list --}}
                                                                <form method="POST" action="{{ route('votes.store', $election) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="voting_item_id" value="{{ $position->id }}">
                                                                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">
                                                                        @foreach($position->options as $candidate)
                                                                            <label style="display:flex; align-items:center; gap:12px; border:2px solid #e5e7eb; border-radius:10px; padding:10px 14px; cursor:pointer; background:white;"
                                                                                   onmouseover="this.style.borderColor='#93c5fd'; this.style.background='#f8faff';"
                                                                                   onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#e5e7eb'; this.style.background='white';}"
                                                                                   onclick="this.closest('form').querySelectorAll('label').forEach(l=>{l.style.borderColor='#e5e7eb';l.style.background='white';}); this.style.borderColor='#2563eb'; this.style.background='#eff6ff';"
                                                                                   class="candidate-row-{{ $position->id }}">
                                                                                <input type="radio" name="election_option_id" value="{{ $candidate->id }}" required
                                                                                       style="width:18px; height:18px; accent-color:#2563eb; flex-shrink:0; cursor:pointer;">
                                                                                @if($candidate->photo_path)
                                                                                    <img src="{{ asset('storage/' . $candidate->photo_path) }}"
                                                                                         style="width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb; flex-shrink:0; display:block;">
                                                                                @else
                                                                                    <div style="width:46px; height:46px; border-radius:50%; background:#f3f4f6; border:2px solid #e5e7eb; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:20px;">👤</div>
                                                                                @endif
                                                                                <div>
                                                                                    <p style="font-weight:700; color:#1f2937; font-size:14px; margin:0;">{{ $candidate->name }}</p>
                                                                                    @if($candidate->description)
                                                                                        <p style="color:#6b7280; font-size:12px; margin:2px 0 0 0;">{{ $candidate->description }}</p>
                                                                                    @endif
                                                                                </div>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button"
                                                                            onclick="document.getElementById('confirm-pos-{{ $position->id }}').style.display='block'"
                                                                            style="background:#2563eb; color:white; padding:9px 20px; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:14px;">
                                                                        Vote for Selected Candidate
                                                                    </button>
                                                                    <div id="confirm-pos-{{ $position->id }}"
                                                                         style="display:none; margin-top:12px; background:#eff6ff; border:1px solid #93c5fd; color:#1e3a8a; padding:15px; border-radius:10px;">
                                                                        <p style="font-weight:700; margin:0 0 5px 0; font-size:14px;">Confirm Your Vote</p>
                                                                        <p style="font-size:13px; margin:0 0 12px 0;">Once submitted, you cannot change your vote for this position.</p>
                                                                        <div style="display:flex; gap:8px;">
                                                                            <button type="submit" style="background:#16a34a; color:white; padding:8px 18px; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Yes, Submit</button>
                                                                            <button type="button" onclick="document.getElementById('confirm-pos-{{ $position->id }}').style.display='none'"
                                                                                    style="background:#6b7280; color:white; padding:8px 18px; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            @endif

                                                        @else
                                                            <p style="color:#9ca3af; font-style:italic; font-size:13px; margin:0;">Voting for this position is currently closed.</p>
                                                        @endif
                                                    </div>
                                                </div>

                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            @endif

                        @empty
                            <p style="color:#9ca3af; font-style:italic; font-size:14px; margin:0;">No active voting sessions in this meeting.</p>
                        @endforelse

                        {{-- Agendas --}}
                        @if($group->agendas->isNotEmpty())
                            <div style="border-top:1px solid #e5e7eb; padding-top:16px; margin-top:4px;">
                                <p style="font-size:12px; font-weight:700; color:#9ca3af; margin:0 0 10px 0; text-transform:uppercase; letter-spacing:0.07em;">📄 Meeting Agendas</p>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    @foreach($group->agendas as $agenda)
                                        <div style="display:flex; align-items:center; justify-content:space-between; border:1px solid #e5e7eb; border-radius:10px; padding:10px 14px; background:#fafafa; gap:10px; flex-wrap:wrap;">
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <span style="font-size:22px;">{{ $agenda->file_type === 'pdf' ? '📕' : '📝' }}</span>
                                                <div>
                                                    <p style="font-size:14px; font-weight:600; color:#1f2937; margin:0;">{{ $agenda->title }}</p>
                                                    <p style="font-size:11px; color:#9ca3af; margin:2px 0 0 0;">{{ strtoupper($agenda->file_type) }} · {{ $agenda->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('agendas.preview', $agenda) }}" target="_blank"
                                               style="background:#d97706; color:white; padding:7px 16px; border-radius:7px; text-decoration:none; font-size:13px; font-weight:700; white-space:nowrap; flex-shrink:0;">
                                                Preview →
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @empty
                <div style="background:white; border-radius:18px; box-shadow:0 2px 16px rgba(0,0,0,0.08); padding:40px; text-align:center;">
                    <p style="color:#9ca3af; font-size:16px; margin:0;">You have not been added to any meeting yet. Please contact your administrator.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
