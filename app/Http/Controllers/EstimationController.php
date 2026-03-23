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
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', fn($q) => $q->where('approval_status', 'Yes'))
                ->latest()->get();
        } else {
            $userId = $user->id;
            $leads = Lead::with(['siteVisit', 'latestQuotation'])
                ->whereHas('siteVisit', fn($q) => $q
                    ->where('assigned_staff', $userId)
                    ->where('approval_status', 'Yes'))
                ->latest()->get();
        }

        return view('Estimator.estimations', [
            'leads'         => $leads,
            'totalAssigned' => $leads->count(),
        ]);
    }

    public function createQuotation(Lead $lead)
    {
        $siteVisit      = $lead->siteVisit;
        $inventoryItems = InventoryStock::select('id', 'item_name', 'price', 'category', 'gst_percentage')->get();
        $services       = \App\Models\Service::all();
        $estimation     = \App\Models\Estimation::where('lead_id', $lead->id)->first();
        $existingQuotation = \App\Models\Quotation::where('lead_id', $lead->id)->first();

        if ($estimation) {
            $estimationItems = \App\Models\EstimationItem::where('estimation_id', $estimation->id)
                ->orderBy('sort_order')->get();

            $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

            $items = $estimationItems->map(fn($item) => [
                'item_id'        => $item->item_id,
                'item_name'      => $item->name ?? '',
                'custom_name'    => $item->item_id ? '' : ($item->name ?? ''),
                'category'       => $item->category   ?? '',
                'description'    => $item->description ?? '',
                'quantity'       => $item->qty,
                'unit'           => $item->unit,
                'price'          => $item->unit_price,
                'gst_percentage' => $item->gst ?? 0,

            ])->toArray();
        } else {
            $last = \App\Models\Estimation::latest()->first();
            $next = $last ? (intval(substr($last->estimation_no, -4)) + 1) : 1;
            $quotationNo = 'QT-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            $items = [];
        }

        return view('Estimator.create-quotation', compact(
            'lead', 'siteVisit', 'inventoryItems',
            'quotationNo', 'existingQuotation', 'items', 'estimation', 'services'
        ));
    }

    public function storeQuotation(Request $request)
    {
        try {
            $leadId = $request->lead_id;
            if (!$leadId) return response()->json(['success' => false, 'message' => 'Lead ID missing']);

            $items    = $request->items    ?? [];
            $services = $request->services ?? [];

            if (empty($items) && empty($services)) {
                return response()->json(['success' => false, 'message' => 'No items or services added']);
            }

            $lead        = \App\Models\Lead::findOrFail($leadId);
            $itemsToSave = [];
            $subtotal    = 0;
            $gstAmount   = 0;

            /* ── Inventory Items ── */
            foreach ($items as $item) {
                $itemId        = $item['item_id'] ?? null;
                $itemName      = '';
                $gstPercentage = 0;
                $qty           = floatval($item['quantity'] ?? 0);
                $price         = floatval($item['price']    ?? 0);

                if (!$itemId && !empty($item['custom_name'])) {
                    $inv    = \App\Models\InventoryStock::create([
                        'item_name' => $item['custom_name'],
                        'category'  => $item['category'] ?? '',
                        'unit'      => $item['unit']      ?? '',
                        'price'     => $price,
                        'quantity'  => 0,
                    ]);
                    $itemId   = $inv->id;
                    $itemName = $inv->item_name;
                }

                if ($itemId) {
                    $inv = \App\Models\InventoryStock::find($itemId);
                    if ($inv) {
                        $itemName      = $inv->item_name;
                        $gstPercentage = $item['gst_percentage'] ?? ($inv->gst_percentage ?? 0);
                    }
                }

                $itemSubtotal = $qty * $price;
                $itemGst      = ($itemSubtotal * $gstPercentage) / 100;
                $subtotal    += $itemSubtotal;
                $gstAmount   += $itemGst;

                $itemsToSave[] = [
                    'item_id'     => $itemId,
                    'name'        => !empty($item['custom_name']) ? $item['custom_name'] : $itemName,
                    'section'     => 'General',
                    'description' => $item['description'] ?? '',
                    'category'    => $item['category']    ?? '',
                    'unit'        => $item['unit'],
                    'qty'         => $qty,
                    'unit_price'  => $price,
                    'amount'      => $itemSubtotal + $itemGst,
                    'length'      => $item['length']         ?? null,
                    'breadth'     => $item['breadth']        ?? null,
                    'area'        => $item['area']           ?? null,
                    'gst'         => $gstPercentage,
                    'gst_amount'  => $itemGst,
                    'service_id'  => null,
                    'service_tax' => 0,
                    'sub_items'   => [],
                ];
            }

            /* ── Service Items ── */
            $servicesToSave = [];
            foreach ($services as $svc) {
                $service = \App\Models\Service::find($svc['service_id'] ?? null);
                if (!$service) continue;

                $svcPrice  = floatval($svc['price']      ?? 0);
                $svcGst    = floatval($svc['gst']        ?? 0);
                $svcGstAmt = floatval($svc['gst_amount'] ?? 0);
                $svcTax    = floatval($svc['tax']        ?? 0);
                $svcTotal  = floatval($svc['total']      ?? 0);
                $svcNote   = $svc['note'] ?? '';

                $subtotal  += $svcTotal;
                $gstAmount += $svcGstAmt;

                // Collect sub-items
                $subItems = [];
                foreach (($svc['sub_items'] ?? []) as $si) {
                    if (empty($si['name'])) continue;
                    $subItems[] = [
                        'name'        => $si['name'],
                        'material'    => $si['material']    ?? '',
                        'unit'        => $si['unit']        ?? '',
                        'size'        => $si['size']        ?? '',
                        'mrp'         => floatval($si['mrp'] ?? 0),
                        'offer_price' => floatval($si['offer_price'] ?? 0),
                    ];
                }

                $servicesToSave[] = [
                    'service_id'   => $service->id,
                    'service_name' => $service->service_name,
                    'section'      => 'Service',
                    'note'         => $svcNote,
                    'price'        => $svcPrice,
                    'gst'          => $svcGst,
                    'gst_amount'   => $svcGstAmt,
                    'service_tax'  => $svcTax,
                    'total'        => $svcTotal,
                    'sub_items'    => $subItems,
                ];
            }

            $grandTotal = $subtotal;

            /* ── Estimation number ── */
            $lastEst = \App\Models\Estimation::latest()->first();
            $next    = $lastEst ? intval(substr($lastEst->estimation_no, -4)) + 1 : 1;
            $estNo   = 'EST-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);

            /* ── Create / Update Estimation ── */
            $estimation = \App\Models\Estimation::where('lead_id', $leadId)->first();

            $estData = [
                'subtotal'    => $subtotal,
                'gst_pct'     => 18,
                'gst_amount'  => $gstAmount,
                'grand_total' => $grandTotal,
                'title'       => 'Quotation ' . $request->quotation_no,
                'status'      => 'Sent',
            ];

            if ($estimation) {
                $estimation->update($estData);
            } else {
                $estimation = \App\Models\Estimation::create(array_merge($estData, [
                    'lead_id'       => $leadId,
                    'estimation_no' => $estNo,
                    'client_name'   => $lead->client_name ?? '',
                    'client_email'  => $lead->email       ?? '',
                    'client_phone'  => $lead->phone       ?? '',
                    'site_address'  => $lead->site_address ?? '',
                    'discount'      => 0,
                    'created_by'    => Auth::id(),
                ]));
            }

            /* ── Save Estimation Items ── */
            \App\Models\EstimationItem::where('estimation_id', $estimation->id)->delete();
            $sortOrder = 1;

            // General items
            foreach ($itemsToSave as $item) {
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
                    'sort_order'    => $sortOrder++,
                    'length'        => $item['length']     ?? null,
                    'breadth'       => $item['breadth']    ?? null,
                    'area'          => $item['area']       ?? null,
                    'gst'           => $item['gst']        ?? null,
                    'gst_amount'    => $item['gst_amount'] ?? null,
                    'service_id'    => null,
                    'service_tax'   => 0,
                ]);
            }

            // Service items — one estimation_item per service (section header row)
            // sub_items stored as JSON in description field
            foreach ($servicesToSave as $svc) {
                \App\Models\EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'item_id'       => null,
                    'name'          => $svc['service_name'],
                    'section'       => 'Service',
                    'description'   => $svc['note'],
                    'category'      => \App\Models\Service::find($svc['service_id'])?->category_service ?? '',
                    'unit'          => 'Nos',
                    'qty'           => 1,
                    'unit_price'    => $svc['price'],
                    'amount'        => $svc['total'],
                    'sort_order'    => $sortOrder++,

                    'gst'           => $svc['gst'],
                    'gst_amount'    => $svc['gst_amount'],
                    'service_id'    => $svc['service_id'],
                    'service_tax'   => $svc['service_tax'],
                    // sub_items stored as JSON in a new meta column OR we use description
                    // Using sub_items_json column — add migration if needed,
                    // or fallback to encoding in description:
                    // 'sub_items_json' => json_encode($svc['sub_items']),
                ]);

                // Save each sub-item as its own estimation_item row with section = 'ServiceItem'
                foreach ($svc['sub_items'] as $si) {
                    \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'item_id'       => null,
                        'name'          => $si['name'],
                        'section'       => 'ServiceItem',
                        'description'   => ($si['material'] ?? '') . '||' . ($si['size'] ?? ''),
                        'category'      => $si['material'] ?? '',
                        'unit'          => $si['unit']     ?? '',
                        'qty'           => 1,
                        'unit_price'    => floatval($si['mrp']         ?? 0),
                        'amount'        => floatval($si['offer_price'] ?? 0),
                        'offer_price'   => floatval($si['offer_price'] ?? 0),
                        'sort_order'    => $sortOrder++,
                        'size'          => $si['size'] ?? null,
                        'gst'           => 0,
                        'gst_amount'    => 0,
                        'service_id'    => $svc['service_id'],
                        'service_tax'   => 0,
                        // Store MRP in a notes-style field (using description second part after ||)
                        // Or add a `mrp` column via migration
                    ]);
                }
            }

            return response()->json([
                'success'       => true,
                'message'       => 'Estimation saved successfully',
                'estimation_id' => $estimation->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate PDF — groups items by section for the new PDF layout
     */
    public function estimationPdf($id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $lead       = \App\Models\Lead::find($estimation->lead_id);
        $dbItems    = \App\Models\EstimationItem::where('estimation_id', $id)
                        ->orderBy('sort_order')->get();

        $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

        /*
         * Build the items array for the PDF blade.
         *
         * section = 'General'     → inventory item row
         * section = 'Service'     → service header row  (carries base_price, gst, tax, note)
         * section = 'ServiceItem' → sub-item row inside a service
         */
        $items = [];

        foreach ($dbItems as $row) {
            $section = $row->section ?? 'General';

            if ($section === 'General') {
                $items[] = [
                    'section'        => 'General',
                    'item_name'      => $row->name ?? $row->description,
                    'category'       => $row->category    ?? '',
                    'description'    => $row->description ?? '',
                    'unit'           => $row->unit        ?? '',
                    'quantity'       => $row->qty,
                    'price'          => $row->unit_price,
                    'gst_percentage' => $row->gst         ?? 0,

                    'service_tax'    => $row->service_tax ?? 0,
                    'gst_amount'     => $row->gst_amount  ?? 0,
                ];
            } elseif ($section === 'Service') {
                $items[] = [
                    'section'           => 'Service',
                    'item_name'         => $row->name,
                    'service_name'      => $row->name,
                    'service_id'        => $row->service_id,
                    'service_note'      => $row->description ?? '',
                    'service_base_price'=> $row->unit_price,
                    'gst_percentage'    => $row->gst         ?? 0,
                    'service_tax'       => $row->service_tax ?? 0,
                    'gst_amount'        => $row->gst_amount  ?? 0,
                    // sub_items will be filled from ServiceItem rows below
                ];
            } elseif ($section === 'ServiceItem') {
                // Parse description: "material||size"
                $parts    = explode('||', $row->description ?? '');
                $material = $parts[0] ?? '';
                $size     = $parts[1] ?? '';

                $items[] = [
                    'section'     => 'ServiceItem',
                    'service_id'  => $row->service_id,
                    'item_name'   => $row->name,
                    'material'    => $material,
                    'unit'        => $row->unit        ?? '',
                    'size'        => $row->size ?? ($parts[1] ?? ''),
                    'unit_price'  => $row->unit_price,                                           
                    'offer_price' => $row->offer_price > 0 ? $row->offer_price : $row->amount,
                    'mrp'         => $row->unit_price,
                    'description' => '',
                ];
            }
        }

        /*
         * Re-structure: the PDF blade expects service items to be flat with
         * 'service_name' set per row. Merge ServiceItem rows into their
         * parent Service row's context by duplicating service metadata.
         */
        $flatItems = [];
        $currentService = null;

        foreach ($items as $item) {
            if ($item['section'] === 'Service') {
                $currentService = $item;
                $flatItems[] = $item;
            } elseif ($item['section'] === 'ServiceItem' && $currentService) {
                // Attach service context so PDF blade can group properly
                $flatItems[] = array_merge($item, [
                    'service_name'       => $currentService['service_name'],
                    'service_note'       => $currentService['service_note'],
                    'service_base_price' => $currentService['service_base_price'],
                    'gst_percentage'     => $currentService['gst_percentage'],
                    'service_tax'        => $currentService['service_tax'],
                ]);
            } else {
                $flatItems[] = $item;
            }
        }

        $pdf = Pdf::loadView('Estimator.quotationpdf', [
            'quotation'   => $estimation,
            'lead'        => $lead,
            'quotationNo' => $quotationNo,
            'items'       => $flatItems,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('quotation-' . $quotationNo . '.pdf');
    }

    public function getServiceItems($serviceId)
    {
        $items = \App\Models\ServiceItem::where('service_id', $serviceId)
                    ->where('status', 1)
                    ->orderBy('id')
                    ->get(['id', 'service_id', 'item_name', 'default_price', 'unit', 'description']);

        return response()->json(['items' => $items]);
    }

    public function getItemDetails(Request $request)
    {
        if (!$request->item_name) return response()->json(['success' => false]);
        $item = InventoryStock::where('item_name', 'LIKE', '%' . $request->item_name . '%')->first();
        if ($item) {
            return response()->json([
                'success'        => true,
                'item_id'        => $item->id,
                'category'       => $item->category,
                'unit'           => $item->unit,
                'price'          => $item->price,
                'gst_percentage' => $item->gst_percentage,
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function updateStatus(Request $request, $id)
    {
        $estimation = Estimation::findOrFail($id);
        $estimation->status = $request->status;
        $estimation->save();

        if ($estimation->lead_id) {
            if ($request->status === 'Approved') Lead::where('id', $estimation->lead_id)->update(['status' => 'Won']);
            if ($request->status === 'Rejected') Lead::where('id', $estimation->lead_id)->update(['status' => 'Lost']);
        }

        return response()->json(['success' => true]);
    }

    public function assignDesigner(Request $request, $id)
    {
        $estimation = \App\Models\Estimation::findOrFail($id);
        $estimation->designer_id = $request->designer_id ?: null;
        $estimation->save();
        return response()->json(['success' => true]);
    }
}
