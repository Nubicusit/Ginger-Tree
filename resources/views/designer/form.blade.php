@extends('designer.layout.app')

@section('title', isset($record) ? 'Edit 3D Design' : 'New 3D Design Record')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
* { font-family: 'Inter', sans-serif; }

.form-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.form-input {
    width: 100%;
    padding: 9px 13px;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    color: #111827;
    background: #FAFAFA;
    outline: none;
    transition: border 0.2s, background 0.2s;
    box-sizing: border-box;
}
.form-input:focus {
    border-color: #6366F1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
}
.form-input.is-error { border-color: #F43F5E; }

.toggle-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: #FAFAFA;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    cursor: pointer;
    transition: border 0.2s;
}
.toggle-wrap:has(input:checked) { border-color: #6366F1; background: #EEF2FF; }

.toggle-track {
    position: relative;
    width: 40px;
    height: 22px;
    background: #D1D5DB;
    border-radius: 999px;
    transition: background 0.2s;
    flex-shrink: 0;
}
.toggle-track.on { background: #6366F1; }
.toggle-thumb {
    position: absolute;
    top: 3px; left: 3px;
    width: 16px; height: 16px;
    background: #fff;
    border-radius: 50%;
    transition: left 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.toggle-thumb.on { left: 21px; }

.section-card {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.section-title {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 1.5px solid #F3F4F6;
    display: flex;
    align-items: center;
    gap: 8px;
}

.error-msg { color: #BE123C; font-size: 11.5px; margin-top: 4px; }

.btn-submit {
    padding: 11px 32px;
    border-radius: 9px;
    border: none;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(99,102,241,0.35);
    transition: opacity 0.2s;
    text-decoration: none;
}
.btn-submit:hover { opacity: 0.9; }

.btn-cancel {
    padding: 11px 24px;
    border-radius: 9px;
    border: 1.5px solid #E5E7EB;
    background: #fff;
    color: #6B7280;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: border 0.2s;
}
.btn-cancel:hover { border-color: #9CA3AF; color: #374151; }

.rule-banner {
    background: linear-gradient(135deg, #FFF7ED, #FFFBEB);
    border: 1px solid #FDE68A;
    border-left: 4px solid #F59E0B;
    border-radius: 10px;
    padding: 13px 18px;
    font-size: 13px;
    color: #92400E;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
</style>

<div style="background:#F8F7F4; min-height:100vh; padding: 28px 24px;">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="flex items-center gap-3 mb-1">

                <h1 style="font-size:20px;font-weight:700;color:#111827;margin:0;">
                    {{ isset($record) ? 'Edit 3D Design Record' : 'New 3D Design Record' }}
                </h1>
            </div>

        </div>
        <a href="{{ route('design-status.index') }}" class="btn-cancel">← Back</a>
    </div>

    {{-- Flash --}}
    @if(session('error'))
        <div style="background:#FFF1F2;border:1px solid #FECDD3;border-left:4px solid #F43F5E;border-radius:10px;padding:12px 16px;color:#BE123C;font-size:13px;margin-bottom:16px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ── Form ── --}}
    <form method="POST"
          action="{{ isset($record) ? route('three-d-design.update', $record->id) : route('three-d-design.store') }}">
        @csrf
        @if(isset($record)) @method('PUT') @endif

        {{-- SECTION 1 — Project Info --}}
        <div class="section-card">
            <div class="section-title">🏗️ Project Information</div>
            <div class="grid grid-cols-1 gap-5" style="display:grid;grid-template-columns:repeat(2,1fr);gap:18px;">

                {{-- Project --}}
                <div style="grid-column:1/-1;">
                    <label class="form-label">Project <span style="color:#F43F5E;">*</span></label>
                    <select name="project_id" class="form-input {{ $errors->has('project_id') ? 'is-error' : '' }}">
                        <option value="">— Select Project —</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id', $record->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                                @if($project->client) — {{ $project->client }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Design Start Date --}}
                <div>
                    <label class="form-label">Design Start Date</label>
                    <input type="date" name="design_start_date"
                           value="{{ old('design_start_date', isset($record) ? $record->design_start_date : '') }}"
                           class="form-input {{ $errors->has('design_start_date') ? 'is-error' : '' }}">
                    @error('design_start_date') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Assigned Designer --}}
                <div>
                    <label class="form-label">Assigned Designer</label>
                    <input type="text" name="assigned_designer"
                           value="{{ old('assigned_designer', $record->assigned_designer ?? '') }}"
                           placeholder="e.g. Priya Menon"
                           class="form-input {{ $errors->has('assigned_designer') ? 'is-error' : '' }}">
                    @error('assigned_designer') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Client Requirements Freeze Date --}}
                <div>
                    <label class="form-label">Client Requirements Freeze Date</label>
                    <input type="date" name="client_requirements_freeze"
                           value="{{ old('client_requirements_freeze', isset($record) ? $record->client_requirements_freeze : '') }}"
                           class="form-input {{ $errors->has('client_requirements_freeze') ? 'is-error' : '' }}">
                    @error('client_requirements_freeze') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- SECTION 2 — Design Status --}}
        <div class="section-card">
            <div class="section-title">📐 Design Status</div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:18px;">

                {{-- Design Status --}}
                <div>
                    <label class="form-label">Design Status <span style="color:#F43F5E;">*</span></label>
                    <select name="design_status" class="form-input {{ $errors->has('design_status') ? 'is-error' : '' }}">
                        @foreach(['In Progress', 'Submitted', 'Revised'] as $s)
                            <option value="{{ $s }}"
                                {{ old('design_status', $record->design_status ?? 'In Progress') === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    @error('design_status') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Revision Count --}}
                <div>
                    <label class="form-label">Revision Count</label>
                    <input type="number" name="revision_count" min="0" max="99"
                           value="{{ old('revision_count', $record->revision_count ?? 0) }}"
                           class="form-input {{ $errors->has('revision_count') ? 'is-error' : '' }}">
                    @error('revision_count') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Client Feedback --}}
                <div style="grid-column:1/-1;">
                    <label class="form-label">Client Feedback</label>
                    <textarea name="client_feedback" rows="3"
                              placeholder="Enter client feedback or revision notes..."
                              class="form-input {{ $errors->has('client_feedback') ? 'is-error' : '' }}"
                              style="resize:vertical;">{{ old('client_feedback', $record->client_feedback ?? '') }}</textarea>
                    @error('client_feedback') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- SECTION 3 — Approval & Freeze --}}
        <div class="section-card">
            <div class="section-title">✅ Approval & Freeze</div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:18px;">

                {{-- Final 3D Approval Toggle --}}
                <div>
                    <label class="form-label">Final 3D Approval</label>
                    @php $approvalVal = old('final_3d_approval', $record->final_3d_approval ?? false); @endphp
                    <label class="toggle-wrap" id="approvalWrap">
                        <input type="hidden" name="final_3d_approval" value="0">
                        <input type="checkbox" name="final_3d_approval" value="1"
                               id="approvalToggle"
                               {{ $approvalVal ? 'checked' : '' }}
                               style="display:none;"
                               onchange="syncToggle('approvalToggle','approvalTrack','approvalThumb','approvalLabel')">
                        <div id="approvalTrack" class="toggle-track {{ $approvalVal ? 'on' : '' }}">
                            <div id="approvalThumb" class="toggle-thumb {{ $approvalVal ? 'on' : '' }}"></div>
                        </div>
                        <span id="approvalLabel" style="font-size:13px;font-weight:600;color:{{ $approvalVal ? '#15803D' : '#6B7280' }};">
                            {{ $approvalVal ? 'Approved ✓' : 'Not Approved' }}
                        </span>
                    </label>
                </div>

                {{-- Approval Date --}}
                <div>
                    <label class="form-label">Approval Date</label>
                    <input type="date" name="approval_date"
                           value="{{ old('approval_date', isset($record) ? $record->approval_date : '') }}"
                           class="form-input {{ $errors->has('approval_date') ? 'is-error' : '' }}"
                           id="approvalDateField">
                    @error('approval_date') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Design Freeze Toggle --}}
                <div style="grid-column:1/-1;">
                    <label class="form-label">Design Freeze Confirmation</label>
                    @php $freezeVal = old('design_freeze_confirmation', $record->design_freeze_confirmation ?? false); @endphp
                    <label class="toggle-wrap" id="freezeWrap"
                           style="{{ $freezeVal ? 'border-color:#F59E0B;background:#FFFBEB;' : '' }}">
                        <input type="hidden" name="design_freeze_confirmation" value="0">
                        <input type="checkbox" name="design_freeze_confirmation" value="1"
                               id="freezeToggle"
                               {{ $freezeVal ? 'checked' : '' }}
                               style="display:none;"
                               onchange="syncToggle('freezeToggle','freezeTrack','freezeThumb','freezeLabel')">
                        <div id="freezeTrack" class="toggle-track {{ $freezeVal ? 'on' : '' }}"
                             style="{{ $freezeVal ? 'background:#F59E0B;' : '' }}">
                            <div id="freezeThumb" class="toggle-thumb {{ $freezeVal ? 'on' : '' }}"></div>
                        </div>
                        <div>
                            <span id="freezeLabel" style="font-size:13px;font-weight:600;color:{{ $freezeVal ? '#B45309' : '#6B7280' }};">
                                {{ $freezeVal ? '🔒 Design Frozen' : '🔓 Not Frozen' }}
                            </span>
                            <p style="font-size:11px;color:#9CA3AF;margin:2px 0 0;">
                                Once frozen, any design change will incur additional cost.
                            </p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="flex items-center gap-3 justify-end mt-2">
            <a href="{{ route('design-status.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">
                {{ isset($record) ? '💾 Update Record' : '✚ Create Record' }}
            </button>
        </div>
    </form>
</div>
<script>
function syncToggle(checkId, trackId, thumbId, labelId) {
    const checked = document.getElementById(checkId).checked;
    const track   = document.getElementById(trackId);
    const thumb   = document.getElementById(thumbId);
    const label   = document.getElementById(labelId);

    track.classList.toggle('on', checked);
    thumb.classList.toggle('on', checked);

    if (checkId === 'approvalToggle') {
        label.textContent = checked ? 'Approved ✓' : 'Not Approved';
        label.style.color = checked ? '#15803D' : '#6B7280';
        // Auto-set today's date when approved
        if (checked) {
            const d = document.getElementById('approvalDateField');
            if (!d.value) d.value = new Date().toISOString().split('T')[0];
        }
    }

    if (checkId === 'freezeToggle') {
        label.textContent = checked ? '🔒 Design Frozen' : '🔓 Not Frozen';
        label.style.color = checked ? '#B45309' : '#6B7280';
        track.style.background = checked ? '#F59E0B' : '';
        document.getElementById('freezeWrap').style.borderColor = checked ? '#F59E0B' : '';
        document.getElementById('freezeWrap').style.background  = checked ? '#FFFBEB' : '';
    }
}
</script>
@endsection
