@extends('designer.layout.app')

@section('title', '3D Design')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.ui-font {
    font-family: 'Inter', sans-serif;
}
.pip  { width:8px; height:8px; border-radius:2px; display:inline-block; }
.btn-edit-row { opacity:0; transition:opacity 0.15s; }
tr:hover .btn-edit-row { opacity:1; }
</style>

<div class="p-5" style="background:#F8F7F4; min-height:100vh;">

    {{-- ── Page Header ── --}}
    <div class="mb-5">
        <div class="flex items-center gap-3 mb-1">
            <span class="mono" style="color:#000000;padding:3px 14px;font-size:16px;font-weight:600;letter-spacing:0.06em;">
                DESIGN FINALIZATION
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background:#F0FDF4;border:1px solid #86EFAC;border-left:4px solid #22C55E;border-radius:10px;padding:12px 16px;color:#15803D;font-size:13px;margin-bottom:16px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FFF1F2;border:1px solid #FECDD3;border-left:4px solid #F43F5E;border-radius:10px;padding:12px 16px;color:#BE123C;font-size:13px;margin-bottom:16px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ── Filters + Actions Row ── --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">

        {{-- Filter Pills --}}
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('design-status.index') }}"
               class="mono"
               style="padding:6px 16px;border-radius:999px;border:1.5px solid {{ !request('status') ? '#6366F1' : '#E5E7EB' }};background:{{ !request('status') ? '#6366F1' : '#fff' }};color:{{ !request('status') ? '#fff' : '#6B7280' }};font-size:12px;font-weight:600;text-decoration:none;letter-spacing:0.04em;">
                All
            </a>
            @foreach(['In Progress', 'Submitted', 'Revised'] as $s)
            <a href="{{ route('design-status.index', ['status' => $s]) }}"
               class="mono"
               style="padding:6px 16px;border-radius:999px;border:1.5px solid {{ request('status') === $s ? '#6366F1' : '#E5E7EB' }};background:{{ request('status') === $s ? '#6366F1' : '#fff' }};color:{{ request('status') === $s ? '#fff' : '#6B7280' }};font-size:12px;font-weight:600;text-decoration:none;letter-spacing:0.04em;">
                {{ $s }}
            </a>
            @endforeach
        </div>

        {{-- Stats + New Record --}}
        <div class="flex items-center gap-5">
            <div class="mono flex gap-4 text-xs" style="color:#9CA3AF;">
                <span><strong style="color:#111827;">{{ $records->where('can_proceed', true)->count() }}</strong> Ready</span>
                <span><strong style="color:#111827;">{{ $records->total() }}</strong> Total</span>
            </div>
            <a href="{{ route('three-d-design.create') }}"
               class="mono"
               style="padding:9px 22px;border-radius:9px;background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 12px rgba(99,102,241,0.35);">
                + New Record
            </a>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;box-shadow:0 4px 24px rgba(0,0,0,0.05);overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:linear-gradient(to right,#FAFAFA,#F5F3FF);border-bottom:2px solid #E5E7EB;">
                    @foreach(['Project','Start Date','Designer','Req. Freeze','Status','Client Feedback','Revisions','Approval','Approval Date','Frozen','Proceed',''] as $col)
                    <th class="mono" style="padding:12px 14px;text-align:left;font-size:10.5px;font-weight:600;color:#000000;letter-spacing:0.08em;text-transform:uppercase;white-space:nowrap;">
                        {{ $col }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr style="border-bottom:1px solid #F3F4F6;" onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background=''">

                    {{-- Project --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span style="font-family:Georgia,serif;font-size:13px;font-weight:600;color:#111827;">
                            {{ $record->project->name ?? '—' }}
                        </span>
                    </td>

                    {{-- Start Date --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#6B7280;">
                            {{ $record->design_start_date ? \Carbon\Carbon::parse($record->design_start_date)->format('d M Y') : '—' }}
                        </span>
                    </td>

                    {{-- Designer --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#374151;">{{ $record->assigned_designer ?? '—' }}</span>
                    </td>

                    {{-- Req Freeze --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#6B7280;">
                            {{ $record->client_requirements_freeze ? \Carbon\Carbon::parse($record->client_requirements_freeze)->format('d M Y') : '—' }}
                        </span>
                    </td>

                    {{-- Status Badge --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @php
                            $sBg = match($record->design_status) {
                                'In Progress' => ['#FFF7ED','#C2410C','rgba(251,146,60,0.3)','#FB923C'],
                                'Submitted'   => ['#EFF6FF','#1D4ED8','rgba(96,165,250,0.3)','#60A5FA'],
                                'Revised'     => ['#FAF5FF','#7E22CE','rgba(192,132,252,0.3)','#C084FC'],
                                default       => ['#F3F4F6','#6B7280','#E5E7EB','#9CA3AF'],
                            };
                        @endphp
                        <span class="mono" style="background:{{ $sBg[0] }};color:{{ $sBg[1] }};border:1px solid {{ $sBg[2] }};border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $sBg[3] }};display:inline-block;"></span>
                            {{ $record->design_status }}
                        </span>
                    </td>

                    {{-- Feedback --}}
                    <td style="padding:13px 14px;vertical-align:middle;max-width:180px;">
                        @if($record->client_feedback)
                            <span style="font-size:12px;color:#374151;">
                                {{ \Illuminate\Support\Str::limit($record->client_feedback, 60) }}
                            </span>
                        @else
                            <span style="font-size:12px;color:#9CA3AF;font-style:italic;">No feedback yet</span>
                        @endif
                    </td>

                    {{-- Revisions --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($record->revision_count == 0)
                            <span class="mono" style="font-size:12px;color:#9CA3AF;">None</span>
                        @else
                            <div style="display:inline-flex;align-items:center;gap:3px;">
                                @for($i = 0; $i < min($record->revision_count, 5); $i++)
                                    <span class="pip" style="background:{{ $record->revision_count > 1 ? '#FCD34D' : '#D1D5DB' }};"></span>
                                @endfor
                                @if($record->revision_count > 5)
                                    <span class="mono" style="font-size:12px;color:#9CA3AF;">+{{ $record->revision_count - 5 }}</span>
                                @endif
                            </div>
                        @endif
                    </td>

                    {{-- Final Approval --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($record->final_3d_approval)
                            <span class="mono" style="background:#F0FDF4;color:#15803D;border:1px solid rgba(134,239,172,0.5);border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#4ADE80;display:inline-block;"></span>Yes
                            </span>
                        @else
                            <span class="mono" style="background:#FFF1F2;color:#BE123C;border:1px solid rgba(254,205,211,0.5);border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#FB7185;display:inline-block;"></span>No
                            </span>
                        @endif
                    </td>

                    {{-- Approval Date --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#6B7280;">
                            {{ $record->approval_date ? \Carbon\Carbon::parse($record->approval_date)->format('d M Y') : '—' }}
                        </span>
                    </td>

                    {{-- Frozen --}}
                    <td style="padding:13px 14px;vertical-align:middle;text-align:center;font-size:18px;"
                        title="{{ $record->design_freeze_confirmation ? 'Frozen' : 'Not Frozen' }}">
                        {{ $record->design_freeze_confirmation ? '🔒' : '🔓' }}
                    </td>

                    {{-- Proceed --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($record->final_3d_approval && $record->design_freeze_confirmation)
                            <span class="mono" style="background:#F0FDF4;color:#15803D;border:1px solid #86EFAC;border-radius:6px;padding:3px 9px;font-size:11px;font-weight:700;">✓ GO</span>
                        @else
                            <span class="mono" style="background:#FFF1F2;color:#BE123C;border:1px solid #FECDD3;border-radius:6px;padding:3px 9px;font-size:11px;font-weight:700;">HOLD</span>
                        @endif
                    </td>

                    {{-- Edit --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <a href="{{ route('three-d-design.edit', $record->id) }}"
                           class="mono btn-edit-row"
                           style="padding:5px 12px;border-radius:7px;border:1.5px solid #E5E7EB;background:#fff;font-size:11px;color:#6366F1;font-weight:600;text-decoration:none;">
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align:center;padding:48px;color:#9CA3AF;">
                        <p class="mono" style="font-size:13px;margin-bottom:8px;">No 3D design records found.</p>
                        <a href="{{ route('three-d-design.create') }}" style="color:#6366F1;font-size:13px;">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($records->hasPages())
        <div class="mt-3 flex justify-end">{{ $records->links() }}</div>
    @endif

    {{-- Legend --}}
    <div class="mono flex flex-wrap gap-5 mt-4" style="font-size:11px;color:#9CA3AF;">
        <span>🔒 Design Frozen</span>
        <span>🔓 Not Frozen</span>
        <span style="color:#15803D;font-weight:700;">✓ GO</span><span>= Approval Yes + Frozen</span>
        <span style="color:#BE123C;font-weight:700;">HOLD</span><span>= Pending approval or freeze</span>
        <span>■ = Revision (amber = 2+)</span>
    </div>
</div>
@endsection
