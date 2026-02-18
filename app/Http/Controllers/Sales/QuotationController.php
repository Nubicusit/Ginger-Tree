<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Lead $lead)
{
    if (!$lead->siteVisit || $lead->siteVisit->approval_status !== 'Yes') {
        abort(403);
    }

    $pdf = Pdf::loadView('sales_executive.pdf.quotation', [
        'lead' => $lead
    ])->setPaper('a4');

    return $pdf->stream('Quotation-' . $lead->id . '.pdf');
}
public function storequotation(Request $request)
{
    $data = $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'description' => 'required',
        'quantity' => 'required|numeric|min:1',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image'
    ]);

    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('img'), $imageName);
        $data['image'] = $imageName;  // stores just the filename
    }

    $data['total'] = $data['quantity'] * $data['price'];

    $quotation = Quotation::create($data);

    return response()->json([
        'success' => true,
        'quotation_id' => $quotation->id
    ]);
}
public function generatePdf(Quotation $quotation)
{
    $lead = Lead::findOrFail($quotation->lead_id);

    $pdf = Pdf::loadView('sales_executive.pdf.quotation', [
        'lead' => $lead,
        'quotation' => $quotation
    ])->setPaper('a4');

    return $pdf->stream('Quotation-' . $quotation->id . '.pdf');
}
}
