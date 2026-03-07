<?php

namespace App\Http\Controllers;
use App\Models\Lead;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EstimationController extends Controller
{
    public function dashboard()
        {
            return view('Estimator.dashboard');
        }
    public function estimation()
        {
            $userId = Auth::id();

        // Get leads where this estimator is assigned AND approval_status is 'Yes'
        $leads = Lead::with(['siteVisit', 'latestQuotation'])
            ->whereHas('siteVisit', function ($query) use ($userId) {
                $query->where('assigned_staff', $userId)
                      ->where('approval_status', 'Yes');
            })
            ->latest()
            ->get();

        $totalAssigned = $leads->count();
            return view('Estimator.estimations',compact('leads', 'totalAssigned'));
        }
public function createQuotation(Lead $lead)
{
    $siteVisit = $lead->siteVisit;
    $inventoryItems = InventoryStock::select('id','item_name','price','category','gst_percentage')->get();

    $existingQuotation = \App\Models\Quotation::where('lead_id',$lead->id)->first();

    if ($existingQuotation) {
        $quotationNo = $existingQuotation->quotation_no;
        $items = $existingQuotation->items;   // saved items
    } else {
        $lastQuotation = \App\Models\Quotation::latest()->first();
        $nextNumber = $lastQuotation ? (intval(substr($lastQuotation->quotation_no,-4)) + 1) : 1;
        $quotationNo = 'QT-' . date('y') . '-' . str_pad($nextNumber,4,'0',STR_PAD_LEFT);
        $items = [];
    }

    return view('Estimator.create-quotation', compact(
        'lead',
        'siteVisit',
        'inventoryItems',
        'quotationNo',
        'existingQuotation',
        'items'
    ));
}

public function storeQuotation(Request $request)
{
    try {

        $leadId = $request->lead_id;

        if (!$leadId) {
            return response()->json([
                'success' => false,
                'message' => 'Lead ID missing'
            ]);
        }

        $items = $request->items;

        if (!$items || count($items) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items added'
            ]);
        }

        $itemsToSave = [];

        foreach ($items as $item) {

            $itemId = $item['item_id'] ?? null;
            $itemName = '';
            $gstPercentage = 0;

            $qty = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);

            /*
            ----------------------------
            CUSTOM ITEM CREATION
            ----------------------------
            */

            if (!$itemId && !empty($item['custom_name'])) {

                $inventory = \App\Models\InventoryStock::create([
                    'item_name' => $item['custom_name'],
                    'category'  => $item['category'] ?? '',
                    'unit'      => $item['unit'] ?? '',
                    'price'     => $price,
                    'quantity'  => 0
                ]);

                $itemId = $inventory->id;
                $itemName = $inventory->item_name;
                $gstPercentage = 0;
            }

            /*
            ----------------------------
            INVENTORY ITEM
            ----------------------------
            */

            if ($itemId) {

                $inventory = \App\Models\InventoryStock::find($itemId);

                if ($inventory) {
                    $itemName = $inventory->item_name;
                    $gstPercentage = $item['gst_percentage'] ?? ($inventory->gst_percentage ?? 0);
                }
            }

            /*
            ----------------------------
            CALCULATIONS
            ----------------------------
            */

            $subtotal = $qty * $price;

            $gstAmount = ($subtotal * $gstPercentage) / 100;

            $total = $subtotal + $gstAmount;

            /*
            ----------------------------
            SAVE ITEM
            ----------------------------
            */

            $itemsToSave[] = [

                'item_id'        => $itemId,
                'item_name'      => $itemName,
                'category'       => $item['category'] ?? '',
                'description'    => $item['description'] ?? '',
                'quantity'       => $qty,
                'unit'           => $item['unit'] ?? '',
                'length'         => $item['length'] ?? '',
                'breadth'        => $item['breadth'] ?? '',
                'area'           => $item['area'] ?? '',
                'price'          => $price,
                'gst_percentage' => $gstPercentage,
                'subtotal'       => $subtotal,
                'gst_amount'     => $gstAmount,
                'total'          => $total

            ];
        }

        $quotation = \App\Models\Quotation::updateOrCreate(
                [
                    'lead_id' => $leadId,   // ← find by lead_id
                ],
                [
                    'quotation_no' => $request->quotation_no,
                    'items'        => $itemsToSave,
                ]
            );

        return response()->json([
            'success' => true,
            'message' => 'Quotation saved successfully',
            'quotation_id' => $quotation->id
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

public function generatePdf(\App\Models\Quotation $quotation)
{
    $lead = $quotation->lead;
    $quotationNo = $quotation->quotation_no;

    // Decode if still a string (old records)
    $items = $quotation->items;
    if (is_string($items)) {
        $items = json_decode($items, true);
    }

    $pdf = Pdf::loadView(
        'Estimator.quotationpdf',
        compact('quotation', 'lead', 'quotationNo', 'items')
    )->setPaper('a4', 'portrait');

    return $pdf->stream('quotation-' . $quotationNo . '.pdf');
}
public function getItemDetails(Request $request)
{
    $itemName = $request->item_name;

    if (!$itemName) {
        return response()->json([
            'success' => false
        ]);
    }

    $item = InventoryStock::where('item_name', 'LIKE', '%' . $itemName . '%')->first();

    if ($item) {
        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'category' => $item->category,
            'unit' => $item->unit,
            'price' => $item->price,
            'gst_percentage' => $item->gst_percentage
        ]);
    }

    return response()->json([
        'success' => false
    ]);
}
}
