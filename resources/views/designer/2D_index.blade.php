{{-- resources/views/designer/2D_index.blade.php --}}
@extends('designer.layout.app')
@section('title', '2D Design & Working Drawings')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
.ui-font { font-family:'Inter',sans-serif; }
.pip  { width:8px;height:8px;border-radius:2px;display:inline-block; }
.btn-edit-row { opacity:0;transition:opacity 0.15s; }
tr:hover .btn-edit-row { opacity:1; }
</style>

<div class="p-5" style="background:#F8F7F4;min-height:100vh;">

    {{-- Header --}}
    <div class="mb-5">
        <span class="mono" style="color:#000;padding:3px 14px;font-size:16px;font-weight:600;letter-spacing:0.06em;">
            2D DESIGN & WORKING DRAWINGS
        </span>
    </div>

    @if(session('success'))
        <div style="background:#F0FDF4;border:1px solid #86EFAC;border-left:4px solid #22C55E;
                    border-radius:10px;padding:12px 16px;color:#15803D;font-size:13px;margin-bottom:16px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FFF1F2;border:1px solid #FECDD3;border-left:4px solid #F43F5E;
                    border-radius:10px;padding:12px 16px;color:#BE123C;font-size:13px;margin-bottom:16px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Filters + Actions --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('two-d-design.index') }}"
               class="mono"
               style="padding:6px 16px;border-radius:999px;
                      border:1.5px solid {{ !request('status') ? '#6366F1' : '#E5E7EB' }};
                      background:{{ !request('status') ? '#6366F1' : '#fff' }};
                      color:{{ !request('status') ? '#fff' : '#6B7280' }};
                      font-size:12px;font-weight:600;text-decoration:none;letter-spacing:0.04em;">
                All
            </a>
            @foreach(['In Progress','Submitted','Approved'] as $s)
            <a href="{{ route('two-d-design.index', ['status' => $s]) }}"
               class="mono"
               style="padding:6px 16px;border-radius:999px;
                      border:1.5px solid {{ request('status') === $s ? '#6366F1' : '#E5E7EB' }};
                      background:{{ request('status') === $s ? '#6366F1' : '#fff' }};
                      color:{{ request('status') === $s ? '#fff' : '#6B7280' }};
                      font-size:12px;font-weight:600;text-decoration:none;letter-spacing:0.04em;">
                {{ $s }}
            </a>
            @endforeach
        </div>

        <div class="flex items-center gap-5">
            <div class="mono flex gap-4 text-xs" style="color:#9CA3AF;">
                <span><strong style="color:#111827;">{{ $records->where('forwarded_to_production', true)->count() }}</strong> Forwarded</span>
                <span><strong style="color:#111827;">{{ $records->total() }}</strong> Total</span>
            </div>
            <a href="{{ route('two-d-design.create') }}"
               class="mono"
               style="padding:9px 22px;border-radius:9px;
                      background:linear-gradient(135deg,#6366F1,#8B5CF6);
                      color:#fff;font-size:13px;font-weight:600;
                      text-decoration:none;box-shadow:0 4px 12px rgba(99,102,241,0.35);">
                + New Record
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;
                box-shadow:0 4px 24px rgba(0,0,0,0.05);overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:linear-gradient(to right,#FAFAFA,#F5F3FF);border-bottom:2px solid #E5E7EB;">
                    @foreach([
                        'Client','Project','Type','Status','Designer',
                        'PM','Start Date','Submit Date',
                        'PM Review','Client Approval','Revisions',
                        'Int. Approval','Freeze','Forwarded','Files',''
                    ] as $col)
                    <th class="mono"
                        style="padding:12px 14px;text-align:left;font-size:10.5px;
                               font-weight:600;color:#000;letter-spacing:0.08em;
                               text-transform:uppercase;white-space:nowrap;">
                        {{ $col }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr style="border-bottom:1px solid #F3F4F6;"
                    onmouseover="this.style.background='#F5F3FF'"
                    onmouseout="this.style.background=''">

                    {{-- Client --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span style="font-family:Georgia,serif;font-size:13px;font-weight:600;color:#111827;">
                            {{ $rec->project?->lead?->client_name ?? '—' }}
                        </span>
                    </td>

                    {{-- Project --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span style="font-family:Georgia,serif;font-size:13px;font-weight:600;color:#111827;">
                            {{ $rec->project->name ?? '—' }}
                        </span>
                    </td>

                    {{-- Drawing Type --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @php
                            $typeBg = match($rec->drawing_type) {
                                'Working Drawing'  => ['#EFF6FF','#1D4ED8'],
                                'Interior Drawing' => ['#FAF5FF','#7E22CE'],
                                'Detail Drawing'   => ['#FFF7ED','#C2410C'],
                                default            => ['#F3F4F6','#6B7280'],
                            };
                        @endphp
                        <span class="mono"
                              style="background:{{ $typeBg[0] }};color:{{ $typeBg[1] }};
                                     border-radius:6px;padding:3px 9px;font-size:11px;font-weight:600;">
                            {{ $rec->drawing_type }}
                        </span>
                    </td>

                    {{-- Drawing Status --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @php
                            $stBg = match($rec->drawing_status) {
                                'In Progress' => ['#FFF7ED','#C2410C','#FB923C'],
                                'Submitted'   => ['#EFF6FF','#1D4ED8','#60A5FA'],
                                'Approved'    => ['#F0FDF4','#15803D','#4ADE80'],
                                default       => ['#F3F4F6','#6B7280','#9CA3AF'],
                            };
                        @endphp
                        <span class="mono"
                              style="background:{{ $stBg[0] }};color:{{ $stBg[1] }};
                                     border-radius:999px;padding:3px 10px;font-size:11.5px;
                                     font-weight:600;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;">
                            <span style="width:6px;height:6px;border-radius:50%;
                                         background:{{ $stBg[2] }};display:inline-block;"></span>
                            {{ $rec->drawing_status }}
                        </span>
                    </td>

                    {{-- Designer --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#374151;">
                            {{ $rec->assigned_designer ?? '—' }}
                        </span>
                    </td>

                    {{-- PM --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#374151;">
                            {{ $rec->project_manager ?? '—' }}
                        </span>
                    </td>

                    {{-- Start Date --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#6B7280;">
                            {{ $rec->drawing_start_date?->format('d M Y') ?? '—' }}
                        </span>
                    </td>

                    {{-- Submit Date --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <span class="mono" style="font-size:12px;color:#6B7280;">
                            {{ $rec->submission_date?->format('d M Y') ?? '—' }}
                        </span>
                    </td>

                    {{-- PM Review --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($rec->pm_internal_review)
                            <span class="mono"
                                  style="background:#F0FDF4;color:#15803D;border:1px solid rgba(134,239,172,0.5);
                                         border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;
                                         display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#4ADE80;display:inline-block;"></span>
                                Done
                            </span>
                        @else
                            <span class="mono"
                                  style="background:#FFF1F2;color:#BE123C;border:1px solid rgba(254,205,211,0.5);
                                         border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;
                                         display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#FB7185;display:inline-block;"></span>
                                Pending
                            </span>
                        @endif
                    </td>

                    {{-- Client Approval --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($rec->client_approval)
                            <span class="mono"
                                  style="background:#F0FDF4;color:#15803D;border:1px solid rgba(134,239,172,0.5);
                                         border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;
                                         display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#4ADE80;display:inline-block;"></span>Yes
                            </span>
                        @else
                            <span class="mono"
                                  style="background:#FFF1F2;color:#BE123C;border:1px solid rgba(254,205,211,0.5);
                                         border-radius:999px;padding:3px 10px;font-size:11.5px;font-weight:600;
                                         display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#FB7185;display:inline-block;"></span>No
                            </span>
                        @endif
                    </td>

                    {{-- Revisions --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($rec->revision_count == 0)
                            <span class="mono" style="font-size:12px;color:#9CA3AF;">None</span>
                        @else
                            <div style="display:inline-flex;align-items:center;gap:3px;">
                                @for($i = 0; $i < min($rec->revision_count, 5); $i++)
                                    <span class="pip"
                                          style="background:{{ $rec->revision_count > 1 ? '#FCD34D' : '#D1D5DB' }};"></span>
                                @endfor
                                @if($rec->revision_count > 5)
                                    <span class="mono" style="font-size:12px;color:#9CA3AF;">
                                        +{{ $rec->revision_count - 5 }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </td>

                    {{-- Internal Approval --}}
                    <td style="padding:13px 14px;vertical-align:middle;text-align:center;font-size:18px;">
                        {{ $rec->internal_approval ? '✅' : '⏳' }}
                    </td>

                    {{-- Design Freeze --}}
                    <td style="padding:13px 14px;vertical-align:middle;text-align:center;font-size:18px;"
                        title="{{ $rec->design_freeze ? 'Frozen' : 'Not Frozen' }}">
                        {{ $rec->design_freeze ? '🔒' : '🔓' }}
                    </td>

                    {{-- Forwarded to Production --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if($rec->forwarded_to_production)
                            <span class="mono"
                                  style="background:#F0FDF4;color:#15803D;border:1px solid #86EFAC;
                                         border-radius:6px;padding:3px 9px;font-size:11px;font-weight:700;">
                                ✓ Forwarded
                            </span>
                            @if($rec->forwarded_date)
                                <div class="mono" style="font-size:10px;color:#9CA3AF;margin-top:2px;">
                                    {{ $rec->forwarded_date->format('d M Y') }}
                                </div>
                            @endif
                        @else
                            <span class="mono"
                                  style="background:#FFF1F2;color:#BE123C;border:1px solid #FECDD3;
                                         border-radius:6px;padding:3px 9px;font-size:11px;font-weight:700;">
                                HOLD
                            </span>
                        @endif
                    </td>

                    {{-- Files --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        @if(!empty($rec->drawing_files))
                            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                                @foreach($rec->drawing_files as $file)
                                @php
                                    $ext     = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png']);
                                    $url     = asset('public/' . $file);
                                    $name    = basename($file);
                                @endphp
                                @if($isImage)
                                    <a href="{{ $url }}" target="_blank" title="{{ $name }}"
                                       style="display:inline-block;width:36px;height:36px;border-radius:6px;
                                              overflow:hidden;border:1.5px solid #DDD6FE;flex-shrink:0;">
                                        <img src="{{ $url }}" alt="{{ $name }}"
                                             style="width:100%;height:100%;object-fit:cover;">
                                    </a>
                                @else
                                    <a href="{{ $url }}" target="_blank" title="{{ $name }}"
                                       style="display:inline-flex;align-items:center;gap:4px;background:#F5F3FF;
                                              border:1px solid #DDD6FE;border-radius:6px;padding:4px 8px;
                                              text-decoration:none;font-size:11px;color:#6366F1;font-weight:500;max-width:120px;">
                                        <span style="font-size:14px;flex-shrink:0;">
                                            {{ $ext === 'pdf' ? '📄' : '📦' }}
                                        </span>
                                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $name }}
                                        </span>
                                    </a>
                                @endif
                                @endforeach
                            </div>
                        @else
                            <span style="font-size:12px;color:#D1D5DB;">—</span>
                        @endif
                    </td>

                    {{-- Edit --}}
                    <td style="padding:13px 14px;vertical-align:middle;">
                        <a href="{{ route('two-d-design.edit', $rec->id) }}"
                           class="mono btn-edit-row"
                           style="padding:5px 12px;border-radius:7px;border:1.5px solid #E5E7EB;
                                  background:#fff;font-size:11px;color:#6366F1;font-weight:600;text-decoration:none;">
                            Edit
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="16" style="text-align:center;padding:48px;color:#9CA3AF;">
                        <p class="mono" style="font-size:13px;margin-bottom:8px;">No 2D design records found.</p>
                        <a href="{{ route('two-d-design.create') }}" style="color:#6366F1;font-size:13px;">
                            Create one →
                        </a>
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
        <span>✅ Internal Approval Done</span>
        <span>⏳ Pending Approval</span>
        <span style="color:#15803D;font-weight:700;">✓ Forwarded</span>
        <span>= Ready for Production</span>
        <span>■ = Revision (amber = 2+)</span>
    </div>
</div>

@endsection
