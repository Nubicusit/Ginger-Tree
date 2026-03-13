<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Estimation;

class EstimationController extends Controller
{
    public function dashboard()
    {
        return view('Estimator.dashboard');
    }

    public function estimation()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all estimations
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', function ($query) {
                    $query->where('approval_status', 'Yes');
                })
                ->latest()
                ->get();
        } else {
            // Estimator sees only their assigned leads
            $userId = $user->id;
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', function ($query) use ($userId) {
                    $query->where('assigned_staff', $userId)
                        ->where('approval_status', 'Yes');
                })
                ->latest()
                ->get();
        }

        $totalAssigned = $leads->count();
        return view('Estimator.estimations', compact('leads', 'totalAssigned'));
    }

    public function createQuotation(Lead $lead)
    {
        $siteVisit = $lead->siteVisit;
        $inventoryItems = InventoryStock::select('id', 'item_name', 'price', 'category', 'gst_percentage')->get();

        $services = \App\Models\Service::all();
        $estimation = \App\Models\Estimation::where('lead_id', $lead->id)->first();
        $existingQuotation = \App\Models\Quotation::where('lead_id', $lead->id)->first();

        if ($estimation) {
            $estimationItems = \App\Models\EstimationItem::where('estimation_id', $estimation->id)
                ->orderBy('sort_order')
                ->get();

            $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

            $items = $estimationItems->map(function ($item) {
                return [
                    'item_id'        => $item->item_id,
                    'item_name'      => $item->name ?? '',
                    'custom_name'    => $item->item_id ? '' : ($item->name ?? ''),
                    'category'       => $item->category ?? '',
                    'description'    => $item->description ?? '',
                    'quantity'       => $item->qty,
                    'unit'           => $item->unit,
                    'price'          => $item->unit_price,
                    'gst_percentage' => $item->gst ?? 0,
                    'length'         => $item->length ?? '',
                    'breadth'        => $item->breadth ?? '',
                    'area'           => $item->area ?? '',
                ];
            })->toArray();
        } else {
            $lastQuotation = \App\Models\Quotation::latest()->first();
            $nextNumber = $lastQuotation ? (intval(substr($lastQuotation->quotation_no, -4)) + 1) : 1;
            $quotationNo = 'QT-' . date('y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $items = [];
        }

        return view('Estimator.create-quotation', compact(
            'lead',
            'siteVisit',
            'inventoryItems',
            'quotationNo',
            'existingQuotation',
            'items',
            'estimation',
            'services'
        ));
    }

    public function storeQuotation(Request $request)
    {
        try {
            $leadId = $request->lead_id;

            if (!$leadId) {
                return response()->json(['success' => false, 'message' => 'Lead ID missing']);
            }

            $items = $request->items ?? [];

            if ((!$items || count($items) == 0) && (!$request->services || count($request->services) == 0)) {
                return response()->json(['success' => false, 'message' => 'No items or services added']);
            }

            $lead = \App\Models\Lead::findOrFail($leadId);

            $itemsToSave = [];
            $subtotal    = 0;
            $gstAmount   = 0;

            foreach ($items as $item) {
                $itemId        = $item['item_id'] ?? null;
                $itemName      = '';
                $gstPercentage = 0;
                $qty           = floatval($item['quantity'] ?? 0);
                $price         = floatval($item['price'] ?? 0);
                $serviceId     = $item['service_id'] ?? null;
                $serviceTax    = $item['service_tax'] ?? 0;

                // Custom item → InventoryStock create
                if (!$itemId && !empty($item['custom_name'])) {
                    $inventory = \App\Models\InventoryStock::create([
                        'item_name' => $item['custom_name'],
                        'category'  => $item['category'] ?? '',
                        'unit'      => $item['unit']      ?? '',
                        'price'     => $price,
                        'quantity'  => 0,
                    ]);
                    $itemId   = $inventory->id;
                    $itemName = $inventory->item_name;
                }

                // Existing inventory item
                if ($itemId) {
                    $inventory = \App\Models\InventoryStock::find($itemId);
                    if ($inventory) {
                        $itemName      = $inventory->item_name;
                        $gstPercentage = $item['gst_percentage'] ?? ($inventory->gst_percentage ?? 0);
                    }
                }

                $itemSubtotal = $qty * $price;
                $itemGst      = ($itemSubtotal * $gstPercentage) / 100;

                $subtotal  += $itemSubtotal;
                $gstAmount += $itemGst;

                $itemsToSave[] = [
                    'item_id'     => $itemId,
                    'name'        => !empty($item['custom_name']) ? $item['custom_name'] : $itemName,
                    'description' => $item['description'] ?? '',
                    'category'    => $item['category']    ?? '',
                    'unit'        => $item['unit'],
                    'qty'         => $qty,
                    'unit_price'  => $price,
                    'amount'      => $itemSubtotal + $itemGst,
                    'length'      => $item['length']         ?? null,
                    'breadth'     => $item['breadth']        ?? null,
                    'area'        => $item['area']           ?? null,
                    'gst'         => $item['gst_percentage'] ?? $gstPercentage,
                    'gst_amount'  => $itemGst,
                    'service_id'  => $serviceId,
                    'service_tax' => $serviceTax,
                ];
            }

            $gstPct     = 18;
            $grandTotal = $subtotal + $gstAmount;

            // ── Estimation number generate ──
            $lastEstimation = \App\Models\Estimation::latest()->first();
            $nextNumber     = $lastEstimation
                ? intval(substr($lastEstimation->estimation_no, -4)) + 1
                : 1;
            $estimationNo = 'EST-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // ── Estimation create or update ──
            $estimation = \App\Models\Estimation::where('lead_id', $leadId)->first();

            if ($estimation) {
                $estimation->update([
                    'subtotal'    => $subtotal,
                    'gst_pct'     => $gstPct,
                    'gst_amount'  => $gstAmount,
                    'grand_total' => $grandTotal,
                    'title'       => 'Quotation ' . $request->quotation_no,
                    'status'      => 'Sent',
                ]);
            } else {
                $estimation = \App\Models\Estimation::create([
                    'lead_id'       => $leadId,
                    'estimation_no' => $estimationNo,
                    'client_name'   => $lead->client_name ?? '',
                    'client_email'  => $lead->email        ?? '',
                    'client_phone'  => $lead->phone        ?? '',
                    'site_address'  => $lead->site_address ?? '',
                    'title'         => 'Quotation ' . $request->quotation_no,
                    'status'        => 'Sent',
                    'subtotal'      => $subtotal,
                    'discount'      => 0,
                    'gst_pct'       => $gstPct,
                    'gst_amount'    => $gstAmount,
                    'grand_total'   => $grandTotal,
                    'created_by'    => Auth::id(),
                ]);
            }

            // ── EstimationItems save ──
            \App\Models\EstimationItem::where('estimation_id', $estimation->id)->delete();

            foreach ($itemsToSave as $index => $item) {
                \App\Models\EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'item_id'       => $item['item_id']    ?? null,
                    'name'          => $item['name'],
                    'section'       => 'General',
                    'description'   => $item['description'] ?? '',
                    'category'      => $item['category']   ?? null,
                    'unit'          => $item['unit'],
                    'qty'           => $item['qty'],
                    'unit_price'    => $item['unit_price'],
                    'amount'        => $item['amount'],
                    'sort_order'    => $index + 1,
                    'length'        => $item['length']      ?? null,
                    'breadth'       => $item['breadth']     ?? null,
                    'area'          => $item['area']        ?? null,
                    'gst'           => $item['gst']         ?? null,
                    'gst_amount'    => $item['gst_amount']  ?? null,
                    'service_id'    => $item['service_id']  ?? null,
                    'service_tax'   => $item['service_tax'] ?? 0,
                ]);
            }

            // ════════════════════════════════════════════════════
            // SERVICES SAVE — NEW ADDITION
            // ════════════════════════════════════════════════════
            if ($request->services && count($request->services) > 0) {
                $sortOffset = count($itemsToSave) + 1;

                foreach ($request->services as $index => $svc) {
                    $service = \App\Models\Service::find($svc['service_id'] ?? null);
                    if (!$service) continue;

                    $svcPrice   = floatval($svc['price']      ?? 0);
                    $svcGstAmt  = floatval($svc['gst_amount'] ?? 0);
                    $svcTax     = floatval($svc['tax']        ?? 0);
                    $svcTotal   = floatval($svc['total']      ?? 0);
                    $svcGstPct  = floatval($svc['gst']        ?? 0);

                    // Add service totals into grand total
                    $grandTotal += $svcTotal;

                    \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'item_id'       => null,
                        'name'          => $service->service_name,
                        'section'       => 'Service',
                        'description'   => '',
                        'category'      => $service->category_service ?? '',
                        'unit'          => 'Nos',
                        'qty'           => 1,
                        'unit_price'    => $svcPrice,
                        'amount'        => $svcTotal,
                        'sort_order'    => $sortOffset + $index,
                        'length'        => null,
                        'breadth'       => null,
                        'area'          => null,
                        'gst'           => $svcGstPct,
                        'gst_amount'    => $svcGstAmt,
                        'service_id'    => $service->id,
                        'service_tax'   => $svcTax,
                    ]);
                }

                // Update grand_total to include service amounts
                $estimation->update(['grand_total' => $grandTotal]);
            }
            // ════════════════════════════════════════════════════
            // END SERVICES SAVE
            // ════════════════════════════════════════════════════

            return response()->json([
                'success'       => true,
                'message'       => 'Estimation saved successfully',
                'estimation_id' => $estimation->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // public function generatePdf(\App\Models\Quotation $quotation)
    // {
    //     $lead = $quotation->lead;
    //     $quotationNo = $quotation->quotation_no;

    //     // Decode if still a string (old records)
    //     $items = $quotation->items;
    //     if (is_string($items)) {
    //         $items = json_decode($items, true);
    //     }

    //     $pdf = Pdf::loadView(
    //         'Estimator.quotationpdf',
    //         compact('quotation', 'lead', 'quotationNo', 'items')
    //     )->setPaper('a4', 'portrait');

    //     return $pdf->stream('quotation-' . $quotationNo . '.pdf');
    // }

    public function estimationPdf($id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $lead       = \App\Models\Lead::find($estimation->lead_id);
        $items      = \App\Models\EstimationItem::where('estimation_id', $id)
                        ->orderBy('sort_order')->get();

        // quotationpdf.blade.php-ൽ $items array format expect ചെയ്യുന്നു
        $itemsArray = $items->map(function ($item) {
            return [
                'item_name'      => $item->name ?? $item->description,
                'category'       => $item->category ?? '',
                'description'    => $item->description ?? '',
                'unit'           => $item->unit ?? '',
                'quantity'       => $item->qty,
                'price'          => $item->unit_price,
                'gst_percentage' => $item->gst ?? 0,
                'length'         => $item->length ?? '',
                'breadth'        => $item->breadth ?? '',
                'area'           => $item->area ?? '',
                'section'        => $item->section ?? 'General',
                'service_tax'    => $item->service_tax ?? 0,
                'gst_amount'     => $item->gst_amount ?? 0,
            ];
        })->toArray();

        $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

        $pdf = Pdf::loadView(
            'Estimator.quotationpdf',
            [
                'quotation'   => $estimation,  // blade-ൽ $quotation use ചെയ്യുന്നിടത്ത്
                'lead'        => $lead,
                'quotationNo' => $quotationNo,
                'items'       => $itemsArray,
            ]
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
                'success'        => true,
                'item_id'        => $item->id,
                'category'       => $item->category,
                'unit'           => $item->unit,
                'price'          => $item->price,
                'gst_percentage' => $item->gst_percentage
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }
    public function updateStatus(Request $request, $id)
{
    $estimation = Estimation::findOrFail($id);

    $estimation->status = $request->status;
    $estimation->save();

    // 🔹 Update lead status
    if ($estimation->lead_id) {

        if ($request->status == 'Approved') {
            Lead::where('id', $estimation->lead_id)
                ->update(['status' => 'Won']);
        }

        if ($request->status == 'Rejected') {
            Lead::where('id', $estimation->lead_id)
                ->update(['status' => 'Lost']);
        }

    }

    return response()->json([
        'success' => true
    ]);
}
}
