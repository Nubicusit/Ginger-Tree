<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\InventoryStock;
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


        try {
            $request->validate([
                'lead_id'              => 'required|exists:leads,id',
                'quotation_no'         => 'required|string',
                'items'                => 'required|array|min:1',
                'items.*.item_id'      => 'required|numeric|min:1',
                'items.*.quantity'     => 'required|numeric|min:1',
                'items.*.price'        => 'required|numeric|min:0',
            ]);

            $lastQuotation = null;
            $quotationNo   = $request->quotation_no;

            foreach ($request->items as $index => $itemData) {

                $imageName = null;

                if ($request->hasFile("items.$index.image")) {
                    $file      = $request->file("items.$index.image");
                    $imageName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $file->move(public_path('quotations'), $imageName);
                    $imageName = 'quotations/' . $imageName;
                }

                $lastQuotation = Quotation::create([
                    'lead_id'      => $request->lead_id,
                    'quotation_no' => $quotationNo,
                    'item'         => $itemData['item_id'],
                    'description'  => $itemData['description'] ?? null,
                    'quantity'     => $itemData['quantity'],
                    'price'        => $itemData['price'],
                    'total'        => $itemData['quantity'] * $itemData['price'],
                    'image'        => $imageName,
                    'status'       => 'Draft',
                ]);
            }

            return response()->json([
                'success'      => true,
                'quotation_id' => $lastQuotation->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_map(fn($msgs) => implode(', ', $msgs), $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
public function generatePdf($quotationId)
{
    // Get ONE row to extract quotation_no + lead_id
    $quotation = Quotation::findOrFail($quotationId);

    // Get ALL items of this quotation
    $quotations = Quotation::where('quotation_no', $quotation->quotation_no)
        ->with('inventoryStock')
        ->get();

    $lead = Lead::findOrFail($quotation->lead_id);

    $pdf = Pdf::loadView('sales_executive.pdf.quotation', [
        'lead'        => $lead,
        'quotations'  => $quotations,
        'quotationNo' => $quotation->quotation_no,
    ])->setPaper('a4');

    return $pdf->stream('Quotation-' . $quotation->quotation_no . '.pdf');
}


public function generateNumber()
{
    $prefix = 'QT';
    $year   = date('Y');

    // Get last quotation number for this year
    $last = Quotation::whereYear('created_at', $year)
        ->latest('id')
        ->first();

    if ($last && $last->quotation_no) {

        $lastNumber = (int) substr($last->quotation_no, -4);
        $newNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '0001';
    }

    $quotationNo = $prefix . '-' . $year . '-' . $newNumber;

    return response()->json(['quotation_no' => $quotationNo]);
}
public function getItems()
{
    return response()->json(
        InventoryStock::select('id', 'item_name', 'price')->get()
    );
}
}
