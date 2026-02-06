<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Carbon\Carbon;
use App\Models\SiteVisit;
class SiteVisitController extends Controller
{


// public function sitevisit()
// {
//     $leads = Lead::with('siteVisit')
//         ->whereHas('siteVisit')
//         ->latest()
//         ->get();

//     return view('sale_executive.Sitevisit', compact('leads'));
// }
    public function store(Request $request)
{
    $data = $request->validate([
        'lead_id' => 'required|exists:leads,id',
    ]);

    $siteVisit = SiteVisit::updateOrCreate(
        ['lead_id' => $request->lead_id],
        $request->all()
    );

    return response()->json([
        'success' => true,
        'data' => $siteVisit
    ]);
}
public function saveSiteVisit(Request $request, Lead $lead)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required'
    ]);

    $datetime = Carbon::parse($request->date . ' ' . $request->time);

    $siteVisit = SiteVisit::updateOrCreate(
        ['lead_id' => $lead->id],
        [
            'lead_id' => $lead->id,
            'visit_datetime' => $datetime
        ]
    );

    // Update Lead Table
    $lead->update([
        'site_visit' => 1,
        'status' => 'Site Visit'
    ]);

    return response()->json([
        'success' => true,
        'data' => $siteVisit
    ]);
}
public function show(Lead $lead)
    {
        // $lead->load('siteVisit');


        return response()->json([
            'client_name' => $lead->client_name,
            'email'       => $lead->email,
            'first_name'  => $lead->first_name,
            'last_name'   => $lead->last_name,
            'site_visit'  => $lead->siteVisit

        ]);
    }

public function storeOrUpdate(Request $request)
{
    $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'measurement_files.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
    ]);

    // ✅ Get existing site visit
    $siteVisit = SiteVisit::where('lead_id', $request->lead_id)->first();

    $existingFiles = [];

if ($siteVisit && is_array($siteVisit->measurement_files)) {
    $existingFiles = $siteVisit->measurement_files;
}


    // ✅ New uploads
    $uploadedFiles = [];

  if ($request->hasFile('measurement_files')) {
    foreach ($request->file('measurement_files') as $file) {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/sitevisits'), $filename);
        $uploadedFiles[] = 'img/sitevisits/' . $filename;
    }
}

    // ✅ Merge old + new
    $allFiles = array_merge($existingFiles, $uploadedFiles);

    $siteVisit = SiteVisit::updateOrCreate(
        ['lead_id' => $request->lead_id],
        [
            'assigned_staff'         => $request->assigned_staff,
            'visit_datetime'         => $request->visit_datetime,
            'site_condition_notes'   => $request->site_condition_notes,
            'space_details'          => $request->space_details,
            'materials_finishes'     => $request->materials_finishes,
            'style_preferences'      => $request->style_preferences,
            'appliances_accessories' => $request->appliances_accessories,
            'brand_preferences'      => $request->brand_preferences,
            'finish_preferences'     => $request->finish_preferences,
            'budget_sensitivity'     => $request->budget_sensitivity,
            'initial_cost_estimate'  => $request->initial_cost_estimate,
            'approval_status'        => $request->approval_status,
            'measurement_files'      => $allFiles, // ✅ MULTIPLE FILES SAVED
        ]
    );

    return response()->json([
        'success' => true,
        'id' => $siteVisit->id
    ]);
}

public function deleteMeasurementFile(Request $request)
{
    $file = $request->file;

    if (!$file) {
        return response()->json(['success' => false]);
    }

    $path = public_path($file);

    if (file_exists($path)) {
        unlink($path);
    }

    SiteVisit::whereJsonContains('measurement_files', $file)
        ->update([
            'measurement_files' => DB::raw("JSON_REMOVE(measurement_files, JSON_UNQUOTE(JSON_SEARCH(measurement_files, 'one', '$file')))")
        ]);

    return response()->json(['success' => true]);
}


}
