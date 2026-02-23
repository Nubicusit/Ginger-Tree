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
    $request->validate([
        'lead_id'         => 'required|exists:leads,id',
        'items'           => 'required|array|min:1',
        'items.*.item'    => 'required|string',
        'items.*.quantity'=> 'required|numeric|min:1',
        'items.*.price'   => 'required|numeric|min:0',
    ]);

    $lastQuotation = null;

    foreach ($request->items as $index => $itemData) {
        $imageName = null;

        if ($request->hasFile("items.{$index}.image")) {
            $file = $request->file("items.{$index}.image");
            $imageName = time() . '_' . $index . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $imageName);
        }

        $lastQuotation = Quotation::create([
            'lead_id'     => $request->lead_id,
            'item'        => $itemData['item'],
            'description' => $itemData['description'] ?? null,
            'quantity'    => $itemData['quantity'],
            'price'       => $itemData['price'],
            'total'       => $itemData['quantity'] * $itemData['price'],
            'image'       => $imageName,
        ]);
    }

    return response()->json([
        'success'      => true,
        'quotation_id' => $lastQuotation->id
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
